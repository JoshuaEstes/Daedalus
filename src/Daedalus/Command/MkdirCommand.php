<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 */
class MkdirCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('mkdir')
            ->setDescription('')
            ->setDefinition(
                array(
                    new InputOption('mode', null, InputOption::VALUE_REQUIRED, 'octal mode', 0700),
                    new InputOption(
                        'directory',
                        null,
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                        'Directories'
                    ),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode        = $input->getOption('mode');
        $directories = $input->getOption('directory');
        $filesystem  = $this->getFilesystem();
        if (!is_array($directories)) {
            $directories = array($directories);
        }

        foreach ($directories as $dir) {
            $output->writeln(
                sprintf('mkdir %s with mode %s', $dir, decoct($mode))
            );
            $fs->mkdir($dir, $mode);
        }
    }

    private function getFilesystem()
    {
        return new Filesystem();
    }
}
