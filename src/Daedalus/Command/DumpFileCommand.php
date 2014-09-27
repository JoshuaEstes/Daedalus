<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class DumpFileCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('dump_file')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('filename', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('content', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('mode', null, InputOption::VALUE_REQUIRED, '', 0666),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFilesystem()->dumpFile(
            $input->getOption('filename'),
            $input->getOption('content'),
            $input->getOption('mode')
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
