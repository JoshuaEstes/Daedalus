<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

/**
 */
class PhpunitCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('phpunit')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('configuration', null, InputOption::VALUE_REQUIRED, '', 'build/'),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process(
            sprintf(
                'phpunit -c %s',
                $input->getOption('configuration')
            )
        );
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
    }
}
