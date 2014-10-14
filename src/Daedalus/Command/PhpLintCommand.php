<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 */
class PhpLintCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('phplint')
            ->setDescription('Runs linter on php files and/or directories')
            ->setDefinition(
                array(
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
        if (!is_array($source)) {
            $source = array($source);
        }
        $output->writeln(
            $formatter->formatBlock('phplint Command', 'info', true)
        );

        $finder = $this->getFinder();
        foreach ($source as $src) {
            $finder->in($src);
            $output->writeln(
                $formatter->formatSection('source', $src)
            );
        }

        foreach ($finder as $file) {
            if ($this->lintFile($file)) {
                $output->writeln(
                    $formatter->formatSection('success', $file)
                );
                continue;
            }
            $output->writeln(
                $formatter->formatSection('<error>FAIL</error>', $file)
            );
            $returnCode = -1;
        }

        return 0;
    }

    protected function getFinder()
    {
        $finder = new Finder();
        $finder->files()->name('*.php');

        return $finder;
    }

    protected function lintFile($file)
    {
        $process = new Process(sprintf('php -l %s', $file));
        $process->run();

        return $process->isSuccessful();
    }
}
