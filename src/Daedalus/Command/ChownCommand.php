<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class ChownCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('chown')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('file', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('user', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('recursive', null, InputOption::VALUE_NONE, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFilesystem()->chown(
            $input->getOption('file'),
            $input->getOption('user'),
            $input->getOption('recursive')
        );
    }

    /**
     * @return Filesystem
     */
    private function getFilesystem()
    {
        return new Filesystem();
    }
}
