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
class ExecCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('exec')
            ->setDescription('Executes a script')
            ->setDefinition(
                array(
                    new InputOption('executable', null, InputOption::VALUE_REQUIRED, 'Path to exectable'),
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
                    'exec',
                    'Starting'
                ),
                $formatter->formatSection(
                    'executable',
                    $input->getOption('executable')
                ),
            )
        );

        $process = new Process(
            sprintf(
                '%s',
                $input->getOption('executable')
            )
        );

        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
    }
}
