<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command used to chmod a file/directory
 */
class ChmodCommand extends Command
{

    /**
     * @todo add help
     */
    protected function configure()
    {
        $this
            ->setName('chmod')
            ->setDescription('chmod a file or folder')
            ->setDefinition(
                array(
                    new InputOption('mode', null, InputOption::VALUE_REQUIRED, 'mode'),
                    new InputOption('file', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'File(s)'),
                )
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getOption('mode');
        $files = $input->getOption('file');
        if (!is_array($files)) {
            $files = array($files);
        }

        foreach ($files as $file) {
            $output->writeln(
                sprintf('chmod %s %s', decoct($mode), $file)
            );
            chmod($file, $mode);
        }
    }
}
