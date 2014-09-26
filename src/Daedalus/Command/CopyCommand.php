<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class CopyCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('copy')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('src', null, InputOption::VALUE_REQUIRED, 'Source'),
                    new InputOption('dest', null, InputOption::VALUE_REQUIRED, 'Destination'),
                    new InputOption('overwrite', null, InputOption::VALUE_NONE, 'Overwrite if exists'),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source      = $input->getOption('src');
        $destination = $input->getOption('dest');
        $overwrite   = $input->getOption('overwrite');

        $this->getFilesystem()->copy($source, $destination, $overwrite);
    }

    /**
     * @return Filesystem
     */
    private function getFilesystem()
    {
        return new Filesystem();
    }
}
