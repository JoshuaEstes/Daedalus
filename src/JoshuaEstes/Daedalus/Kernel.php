<?php

namespace JoshuaEstes\Daedalus;

use JoshuaEstes\Daedalus\Loader\YamlLoader;
use JoshuaEstes\Daedalus\Application;
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

class Kernel
{
    const VERSION       = '0.1.0';
    const VERSION_MAJOR = '0';
    const VERSION_MINOR = '1';
    const VERSION_PATCH = '0';

    protected $booted = false;
    protected $container;
    protected $application;
    protected $input;
    protected $output;

    /**
     * @param InputInterface $input
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Boots kernel by setting up the container
     *
     * @param Application $application
     */
    public function boot(Application $application)
    {
        if (true === $this->booted) {
            return;
        }

        $this->application = $application;

        $this->initializeContainer();

        $this->booted = true;
    }

    /**
     */
    public function shutdown()
    {
        if (false === $this->booted) {
            return;
        }

        $this->booted = false;

        $this->container = null;
        $this->input     = null;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Initialize the container and compile
     */
    protected function initializeContainer()
    {
        $this->container = $this->buildContainer();
        $this->initializeBuildFile();
        $this->container->compile();
        $this->application->add(new \JoshuaEstes\Daedalus\Command\DumpContainerCommand($this->container));
    }

    /**
     * Build the container and get it setup in a basic state
     *
     * @return ContainerBuilder
     */
    protected function buildContainer()
    {
        $container = new ContainerBuilder(new ParameterBag($this->getContainerParameters()));
        $container->set('kernel', $this);
        $container->addObjectResource($this);
        $container->set('application', $this->application);
        $container->addObjectResource($this->application);
        $this->prepareContainer($container);
        $this->getContainerLoader($container)->load('services.xml');

        return $container;
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
    }

    /**
     * @param ContainerInterface $container
     */
    protected function getContainerLoader(ContainerInterface $container)
    {
        return new XmlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
    }

    /**
     * Injected into container
     *
     * @return array
     */
    protected function getContainerParameters()
    {
        return array_merge(
            array(
                'app.start_dir' => getcwd(),
                'user.home'     => getenv('HOME'),
            ),
            $this->getEnvironmentVariables()
        );
    }

    /**
     * Returns an array of various environmental variables
     *
     * @return array
     */
    protected function getEnvironmentVariables()
    {
        return array();
    }

    /**
     * Used to return the location of the build file
     *
     * @return string
     * @throw Exception
     */
    protected function getBuildFile()
    {
        $input = $this->input;
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

    /**
     */
    protected function initializeBuildFile()
    {
        $this->processBuildFile($this->getBuildFile());
    }

    /**
     * Parses and processes a build file, adding new tasks that a developer
     * is able to run
     *
     * @param string $buildfile
     */
    protected function processBuildFile($buildfile)
    {
        // @todo refactor so that the build file can be something other than a yaml
        // @todo verifiy that the parsed file returns an array
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
            $this->application->add($command);
        }

        return $config;
    }

    protected function initializeDispatcher()
    {
    }
}
