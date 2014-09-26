<?php

namespace JoshuaEstes\Daedalus;

use JoshuaEstes\Daedalus\Loader\PropertiesFileLoader;
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
    const VERSION       = '1.0.0';
    const VERSION_MAJOR = '1';
    const VERSION_MINOR = '0';
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

        $this->application->setDispatcher($this->getContainer()->get('event_dispatcher'));

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
        $this->output    = null;
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
        $this->initializePropertiesFile();
        $this->initializeBuildFile();
        $this->container->compile();
        $this->application->add(new \JoshuaEstes\Daedalus\Command\DumpContainerCommand($this->container));
        $this->application->add(new \JoshuaEstes\Daedalus\Command\HelpCommand($this->container));
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
        $this->getContainerLoader($container)->load('services.xml');

        return $container;
    }

    /**
     * @param ContainerInterface $container
     */
    protected function getContainerLoader(ContainerInterface $container)
    {
        $locator = new FileLocator(
            array(
                __DIR__ . '/Resources/config',
            )
        );

        $resolver = new LoaderResolver(
            array(
                new XmlFileLoader($container, $locator),
                new PropertiesFileLoader($container, $locator),
            )
        );

        return new DelegatingLoader($resolver);
    }

    /**
     * Injected into container
     *
     * @return array
     */
    protected function getContainerParameters()
    {
        /**
         * The constants described at http://us2.php.net/manual/en/reserved.constants.php
         * might be useful to include here as well
         */
        return array_merge(
            array(
                'php.version' => PHP_VERSION,
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
        $env = array();

        foreach ($_SERVER as $name => $val) {
            if (is_array($val) || !in_array(strtolower($name), $this->getEnvironmentVariablesThatICareAbout())) {
                // In the future, this could be supported
                continue;
            }

            $env['env.'.$name] = $val;
        }

        return $env;
    }

    /**
     * Injects only a few useful ones into the container
     *
     * @return array
     */
    protected function getEnvironmentVariablesThatICareAbout()
    {
        return array(
            'shell',
            'tmpdir',
            'user',
            'pwd',
            'lang',
            'editor',
            'home',
        );
    }

    /**
     * Used to return the location of the build file
     *
     * @return string
     * @throw Exception
     */
    protected function getBuildFile()
    {
        $buildfile = getcwd() . '/build.yml';

        if (true === $this->input->hasParameterOption('--buildfile')) {
            $buildfile = $this->input->getParameterOption('--buildfile');
        }

        if (is_file($buildfile) && is_readable($buildfile)) {
            return $buildfile;
        }

        throw new \Exception(
            sprintf('Could not find build file "%s"', $buildfile)
        );
    }

    /**
     * Find and process build file
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
        // @todo refactor this entire block
        $processor = new Processor();
        $container = $this->getContainer();
        $config    = $processor->processConfiguration(new Configuration(), Yaml::parse($buildfile));

        foreach ($config['tasks'] as $name => $taskConfig) {
            $command = new Command($name);
            $command->setDescription($taskConfig['description']);
            $command->setCode(function (InputInterface $input, OutputInterface $output) use ($taskConfig, $container) {
                foreach ($taskConfig['commands'] as $command => $commandConfig) {
                    $serviceId = sprintf('command.%s', $commandConfig['command']);
                    if (!$container->has($serviceId)) {
                        $output->writeln(
                            array(
                                sprintf('<error>Could not find command "%s"</error>', $commandConfig['command']),
                            )
                        );
                        continue;
                    }

                    $cmd     = $container->get($serviceId);
                    $options = array();

                    foreach ($commandConfig['arguments'] as $opt => $value) {
                        $options['--'.$opt] = $value;
                    }
                    $cmd->run(new ArrayInput($options), $output);
                }
            });

            $container->get('application')->add($command);
        }

        return $config;
    }

    /**
     * Find, load, parse, inject properties into container
     */
    protected function initializePropertiesFile()
    {
        if (true === $this->input->hasParameterOption('--propertyfile')) {
            $propertyfile = realpath($this->input->getParameterOption('--propertyfile'));
            if (false !== $propertyfile) {
                $this->getContainerLoader($this->container)->load($propertyfile);
            }
        }
    }
}
