<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Command used to chmod a file/directory
 */
class ChmodCommand extends Command
{

    /**
     */
    protected function configure()
    {
        $this
            ->setName('chmod')
            ->setDescription('chmod a file or folder')
            ->setDefinition(
                array(
                    new InputOption('mode', null, InputOption::VALUE_REQUIRED, 'octal mode'),
                    new InputOption(
                        'file',
                        null,
                        InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                        'Files and/or Directories'
                    ),
                )
            )
            ->setHelp(
<<<HELP

Basic Usage

command:   chmod
arguments:
    mode: <octal>
    file: /path/to/file.ext

More advanced usage

command:   chmod
arguments:
    mode: <octal>
    file: ['/path/to/directory', '/path/to/file.ext']

HELP
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode  = $input->getOption('mode');
        $files = $input->getOption('file');
        $fs    = $this->getFilesystem();
        if (!is_array($files)) {
            $files = array($files);
        }

        foreach ($files as $file) {
            $output->writeln(
                sprintf('chmod %s %s', decoct($mode), $file)
            );
            $fs->chmod($file, $mode);
        }
    }

    private function getFilesystem()
    {
        return new Filesystem();
    }
}
