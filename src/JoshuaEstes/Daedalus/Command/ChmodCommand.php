<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 */
class ChmodCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('chmod')
            ->setDescription('chmod a file or folder')
            ->setDefinition(
                array(
                    new InputArgument('mode', InputArgument::REQUIRED, 'mode'),
                    new InputArgument('file', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'File(s)'),
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('CHMOD');
    }
}
