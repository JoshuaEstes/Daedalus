<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

/**
 */
class GitCloneCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('gitclone')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputArgument('src', InputArgument::REQUIRED, 'Git URL'),
                    new InputArgument('dest', InputArgument::REQUIRED, 'Destination path'),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process(
            sprintf('git clone %s %s', $input->getArgument('src'), $input->getArgument('dest'))
        );
        $process->run();

        if ($process->isSuccessful()) {
            return 0;
        }

        return 1;
    }
}
