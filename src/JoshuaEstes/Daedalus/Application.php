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

class Application extends BaseApplication
{

    const VERSION = '0.1.0';

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
    }

    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
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
}
