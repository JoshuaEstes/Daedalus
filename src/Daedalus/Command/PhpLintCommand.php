<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
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
        $source = $input->getArgument('source');
        if (!is_array($source)) {
            $source = array($source);
        }

        $finder = new Finder();
        $finder
            ->files()
            ->name('*.php');

        foreach ($source as $src) {
            $finder->in($src);
        }

        foreach ($finder as $file) {
            if ($this->lintFile($file)) {
                $output->writeln(
                    sprintf('OK "%s"', $file)
                );
                continue;
            }
            $output->writeln(
                sprintf('NOT OK "%s"', $file)
            );
        }
    }

    protected function lintFile($file)
    {
        $process = new Process(sprintf('php -l %s', $file));
        $process->run();

        return $process->isSuccessful();
    }
}
