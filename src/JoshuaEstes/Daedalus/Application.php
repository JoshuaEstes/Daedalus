<?php

namespace JoshuaEstes\Daedalus;

use JoshuaEstes\Daedalus\Loader\YamlLoader;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
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
                new InputOption('buildfile', null, InputOption::VALUE_REQUIRED, 'build file', 'build.yml'),
                new InputOption('propertyfile', null, InputOption::VALUE_REQUIRED, 'Properties file', 'build.properties'),
            )
        );

        $this->initializeContainer();
        $this->add(new \JoshuaEstes\Daedalus\Command\DumpContainerCommand($this->container));
    }

    protected function initializeBuildFile(InputInterface $input, OutputInterface $output)
    {
        $buildFile              = getcwd() . '/build.yml';
        $configs                = Yaml::parse($buildFile);
        $processor              = new Processor();
        $configuration          = new Configuration();
        $processedConfiguration = $processor->processConfiguration($configuration, $configs);
        var_dump($processedConfiguration['tasks']['build']['commands']);
        die();
        // process build file

        return;
        $locator        = new FileLocator(array(getcwd()));
        $loaderResolver = new LoaderResolver(
            array(
                new YamlLoader($locator),
            )
        );
        $delegatingLoader = new DelegatingLoader($loaderResolver);
        $commands         = $delegatingLoader->load(
            $input->getParameterOption('--buildfile', 'build.yml')
        );

        $this->addCommands($commands);
    }

    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        $this->initializeBuildFile($input, $output);
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
