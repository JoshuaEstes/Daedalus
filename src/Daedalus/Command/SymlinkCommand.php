<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class SymlinkCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('symlink')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('origin', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('target', null, InputOption::VALUE_REQUIRED, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFilesystem()->symlink(
            $input->getOption('origin'),
            $input->getOption('target')
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
