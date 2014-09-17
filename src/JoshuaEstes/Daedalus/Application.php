<?php

namespace JoshuaEstes\Daedalus;

use JoshuaEstes\Daedalus\Kernel;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class Application extends BaseApplication
{
    protected $kernel;

    /**
     * Creates a new instance of the app
     */
    public function __construct(Kernel $kernel)
    {
        parent::__construct('Daedalus', Kernel::VERSION);
        $this->kernel = $kernel;

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

    /**
     * @inheritdoc
     *
     * Need to find a better way to hook into this
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        $this->kernel->setInput($input);
        $this->kernel->setOutput($output);
        $this->kernel->boot($this);
    }
}
