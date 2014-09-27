<?php

namespace Daedalus;

use Daedalus\Loader\PropertiesFileLoader;
use Daedalus\Loader\YamlBuildFileLoader;
use Daedalus\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 */
class Kernel
{
    const VERSION       = '1.0.0';
    const VERSION_MAJOR = '1';
    const VERSION_MINOR = '0';
    const VERSION_PATCH = '0';

    const TYPE_BUILD_FILE      = 'build';
    const TYPE_PROPERTIES_FILE = 'properties';

    protected $booted = false;
    protected $container;
    protected $application;
    protected $input;
    protected $output;

    /**
     * Boots kernel by setting up the container
     */
    public function boot(Application $application, InputInterface $input, $output)
    {
        if (true === $this->booted) {
            return;
        }

        $this->application = $application;
        $this->input       = $input;
        $this->output      = $output;

        $this->initializeContainer();
        $this->booted = true;
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

        // Load all the default services
        $this->getContainerLoader($this->container)->load('services.xml');

        $this->container->compile();
    }

    /**
     * Build the container and get it setup in a basic state
     *
     * @return ContainerBuilder
     */
    protected function buildContainer()
    {
        $container = new ContainerBuilder(new ParameterBag($this->getContainerParameters()));
        $container->setResourceTracking(true);
        $container->set('kernel', $this);
        $container->set('application', $this->application);

        // Load the build file
        $this->getContainerLoader($container)->load($this->getBuildFile(), self::TYPE_BUILD_FILE);

        // Load the properties file
        $file = $this->getPropertiesFile();
        if ($file) {
            $this->getContainerLoader($container)->load($file, self::TYPE_PROPERTIES_FILE);
        }

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
                new YamlBuildFileLoader($container, $locator),
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
            $buildfile = realpath($this->input->getParameterOption('--buildfile'));
        }

        if (is_file($buildfile) && is_readable($buildfile)) {
            return $buildfile;
        }

        throw new \Exception(
            sprintf('Could not find build file "%s"', $buildfile)
        );
    }

    /**
     * Finds and returns the file set as the build.properties file.
     *
     * @return string|false
     */
    protected function getPropertiesFile()
    {
        $propertyfile = getcwd() . '/build.properties';

        if (true === $this->input->hasParameterOption('--propertyfile')) {
            $propertyfile = realpath($this->input->getParameterOption('--propertyfile'));
        }

        if (is_file($propertyfile) && is_readable($propertyfile)) {
            return $propertyfile;
        }

        return false;
    }
}
