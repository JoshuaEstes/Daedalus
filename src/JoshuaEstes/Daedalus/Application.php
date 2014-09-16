<?php

namespace JoshuaEstes\Daedalus;

use JoshuaEstes\Daedalus\Loader\YamlLoader;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;

/**
 */
class Application extends BaseApplication
{
    const VERSION       = '0.1.0';
    const VERSION_MAJOR = '0';
    const VERSION_MINOR = '1';
    const VERSION_PATCH = '0';

    protected $container;

    public function __construct()
    {
        parent::__construct('Daedalus', self::VERSION);

        /**
         * Update definition with new options
         */
        $this->getDefinition()->addOptions(
            array(
                new InputOption('buildfile', null, InputOption::VALUE_REQUIRED, 'build file'),
                new InputOption('propertyfile', null, InputOption::VALUE_REQUIRED, 'Properties file'),
            )
        );

    }

    protected function initializeBuildFile(InputInterface $input, OutputInterface $output)
    {
        $config = $this->processBuildFile($this->getBuildFile($input));
    }

    protected function processBuildFile($buildfile)
    {
        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), Yaml::parse($buildfile));

        foreach ($config['tasks'] as $name => $taskConfig) {
            $command = new Command($name);
            $command->setDescription($taskConfig['description']);
            $command->setCode(function ($input, $output) use ($taskConfig) {
                foreach ($taskConfig['commands'] as $command => $commandConfig) {
                    $cmdClass = '\JoshuaEstes\Daedalus\Command\\' . ucfirst($commandConfig['command']) . 'Command';
                    if (!class_exists($cmdClass)) {
                        $output->writeln(
                            array(
                                sprintf('<error>Could not find command "%s"</error>', $commandConfig['command']),
                            )
                        );
                        continue;
                    }
                    $cmd = new $cmdClass();
                    $cmd->run(new ArrayInput($commandConfig['arguments']), $output);
                }
            });
            $this->add($command);
        }

        return $config;
    }

    /**
     * Used to return the location of the build file
     *
     * @return string
     * @throw Exception
     */
    protected function getBuildFile(InputInterface $input)
    {
        $buildfile = getcwd() . '/build.yml';

        if (true === $input->hasParameterOption('--buildfile')) {
            $buildfile = $input->getParameterOption('--buildfile');
        }

        if (is_file($buildfile) && is_readable($buildfile)) {
            return $buildfile;
        }

        throw new \Exception(
            sprintf('Could not find build file "%s"', $buildfile)
        );
    }

    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        $this->initializeBuildFile($input, $output);
        $this->initializeContainer();
        $this->add(new \JoshuaEstes\Daedalus\Command\DumpContainerCommand($this->container));
    }

    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->container->compile();
    }

    protected function buildContainer()
    {
        $container = new ContainerBuilder(new ParameterBag($this->getContainerParameters()));
        $container->set('application', $this);
        $container->addObjectResource($this);
        $this->prepareContainer($container);
        $this->getContainerLoader($container)->load('services.xml');

        return $container;
    }

    protected function prepareContainer(ContainerBuilder $container)
    {
    }

    /**
     * Default build parameters
     */
    protected function getContainerParameters()
    {
        return array(
            'app.start_dir' => getcwd(),
            'user.home'     => getenv('HOME'),
        );
    }

    protected function getContainerLoader(ContainerInterface $container)
    {
        return new XmlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
    }
}
