<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

/**
 */
class PharCommand extends Command
{
    /**
     */
    protected function configure()
    {
        $this
            ->setName('phar')
            ->setDescription('Builds a phar file')
            ->setDefinition(
                array(
                    new InputOption('output', null, InputOption::VALUE_REQUIRED, 'Output file'),
                    new InputOption('stub', null, InputOption::VALUE_REQUIRED, 'Path to a stub file'),
                    new InputOption('compression', null, InputOption::VALUE_REQUIRED, 'gz, bz2, none', 'none'),
                    new InputOption(
                        'signature',
                        null,
                        InputOption::VALUE_REQUIRED,
                        'One of the support signature types'
                    ),
                    new InputOption('key', null, InputOption::VALUE_REQUIRED, 'Path to key'),
                    new InputOption('password', null, InputOption::VALUE_REQUIRED, 'Password for key'),
                    new InputOption('finder', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, ''),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $phar      = new \Phar($input->getOption('output'), 0, basename($input->getOption('output')));
        $formatter = $this->getHelper('formatter');
        $finder    = $this->createFinder($input->getOption('finder'), $output);

        foreach ($finder as $file) {
            $path = strtr(str_replace('/Users/joshuaestes/Projects/joshuaestes/Daedalus/', '', $file->getRealPath()), '\\', '/');
            $path = strtr(preg_replace('/(symfony\/[a-z-]*\/)/', '', $path), '\\', '/');
            $phar->addFile($file->getRealPath(), $path);
            $output->writeln(
                sprintf('%s => %s', $file->getRealPath(), $path)
            );
        }
        $stub = $input->getOption('stub');
        //var_dump(realpath($stub));
        //$phar->setStub($phar->createDefaultStub('cli.php'));
        $phar->setStub(file_get_contents(realpath($input->getOption('stub'))));
    }

    protected function createFinder(array $options, OutputInterface $output)
    {
        $finder = $this->getFinder();
        $output->writeln('Looking for files');

        if (!is_array($options['name'])) {
            $options['name'] = array($options['name']);
        }

        foreach ($options['name'] as $name) {
            $finder->name($name);
            $output->writeln(
                sprintf('-> with name "%s"', $name)
            );
        }

        if (!is_array($options['in'])) {
            $options['in'] = array($options['in']);
        }

        foreach ($options['in'] as $in) {
            $finder->in($in);
            $output->writeln(
                sprintf('-> in directory "%s"', $in)
            );
        }

        if (!is_array($options['exclude'])) {
            $options['exclude'] = array($options['exclude']);
        }

        foreach ($options['exclude'] as $exclude) {
            $finder->exclude($exclude);
            $output->writeln(
                sprintf('-> excluding "%s"', $exclude)
            );
        }

        return $finder;
    }

    /**
     * @return Finder
     */
    private function getFinder()
    {
        $finder = new Finder();
        $finder->files();

        return $finder;
    }
}
