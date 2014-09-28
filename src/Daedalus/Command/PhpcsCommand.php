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
class PhpcsCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('phpcs')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('report', null, InputOption::VALUE_REQUIRED, '', 'full'),
                    new InputOption('standard', null, InputOption::VALUE_REQUIRED, '', 'PSR1,PSR2'),
                    new InputArgument('source', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'file or directory'),
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
        $source     = $input->getArgument('source');
        $output->writeln(
            array(
                $formatter->formatSection(
                    'phpcs',
                    'Starting'
                ),
                $formatter->formatSection(
                    'report',
                    $input->getOption('report')
                ),
                $formatter->formatSection(
                    'standard',
                    $input->getOption('standard')
                ),
            )
        );

        if (!is_array($source)) {
            $source = array($source);
        }

        foreach ($source as $src) {
            $output->writeln($formatter->formatSection('source', $src));
            $process = new Process(
                sprintf(
                    'phpcs --report=%s --standard=%s %s',
                    $input->getOption('report'),
                    $input->getOption('standard'),
                    $src
                )
            );
            $process->run(function ($type, $buffer) use ($output) {
                $output->writeln($buffer);
            });
            if (!$process->isSuccessful()) {
                $returnCode = 1;
            }
        }

        if (0 !== $returnCode) {
            $output->writeln($formatter->formatSection('phpcs', '<error>failed</error>'));

            return $returnCode;
        }

        $output->writeln($formatter->formatSection('phpcs', '<success>success</success>'));

        return $returnCode;
    }
}
