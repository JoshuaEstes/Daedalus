<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class TouchCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('touch')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('file', null, InputOption::VALUE_REQUIRED, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFilesystem()->touch(
            $input->getOption('file')
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
