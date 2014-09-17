<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class ExecCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('exec')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('executable', null, InputOption::VALUE_REQUIRED, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
