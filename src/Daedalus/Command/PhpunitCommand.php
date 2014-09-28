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
                    new InputOption('configuration', null, InputOption::VALUE_REQUIRED, '', 'phpunit.xml.dist'),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->getHelper('formatter');
        $output->writeln(
            array(
                $formatter->formatSection(
                    'phpunit',
                    'Starting'
                ),
                $formatter->formatSection(
                    'configuration',
                    $input->getOption('configuration')
                ),
            )
        );

        $process = new Process(
            sprintf(
                'phpunit -c %s',
                $input->getOption('configuration')
            )
        );

        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });

        if (!$process->isSuccessful()) {
            $output->writeln(
                array(
                    $formatter->formatSection(
                        'phpunit',
                        '<error>FAILED</error>'
                    ),
                )
            );
            return -1;
        }

        $output->writeln(
            array(
                $formatter->formatSection(
                    'phpunit',
                    'SUCCESS'
                ),
            )
        );

        return 0;
    }
}
