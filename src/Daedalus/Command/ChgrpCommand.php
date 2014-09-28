<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class ChgrpCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('chgrp')
            ->setDescription('Changes group on file or directory')
            ->setDefinition(
                array(
                    new InputOption('file', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('group', null, InputOption::VALUE_REQUIRED, ''),
                    new InputOption('recursive', null, InputOption::VALUE_NONE, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $returnCode = 0;
        $formatter  = $this->getHelper('formatter');
        $output->writeln(
            array(
                $formatter->formatSection(
                    'chgrp',
                    'Starting'
                ),
                $formatter->formatSection(
                    'file',
                    $input->getOption('file')
                ),
                $formatter->formatSection(
                    'group',
                    $input->getOption('group')
                ),
                $formatter->formatSection(
                    'recursive',
                    $input->hasOption('recursive') ? 'yes' : 'no'
                ),
            )
        );

        try {
            $this->getFilesystem()->chgrp(
                $input->getOption('file'),
                $input->getOption('group'),
                $input->getOption('recursive')
            );
            $output->writeln(
                $this->getHelper('formatter')->formatSection(
                    'chgrp',
                    'SUCCESS'
                )
            );
        } catch (\Exception $e) {
            $returnCode = -1;
            $output->writeln(
                $this->getHelper('formatter')->formatSection(
                    'chgrp',
                    'FAILED'
                )
            );
        }


        return $returnCode;
    }

    /**
     * @return Filesystem
     */
    private function getFilesystem()
    {
        return new Filesystem();
    }
}
