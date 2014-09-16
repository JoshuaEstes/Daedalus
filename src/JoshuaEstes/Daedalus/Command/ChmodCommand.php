<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Command used to chmod a file/directory
 */
class ChmodCommand extends Command
{

    /**
     * @todo add help
     */
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

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            array(
                $input->getArgument('mode'),
                $input->getArgument('file'),
            )
        );
    }
}
