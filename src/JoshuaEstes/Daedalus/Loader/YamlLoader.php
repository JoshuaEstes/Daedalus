<?php

namespace JoshuaEstes\Daedalus\Loader;

use JoshuaEstes\Daedalus\Command as DaedalusCommand;
use JoshuaEstes\Daedalus\Task;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads the tasks in a yaml file and returns an array of commands
 */
class YamlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $configValues = Yaml::parse($resource);
        $tasks        = array();

        foreach ($configValues['daedalus']['tasks'] as $task => $taskConfig) {
            $cmd = new Command($task);
            $cmd->setCode(function ($input, $output) use ($taskConfig) {
                $cmdClass = '\JoshuaEstes\Daedalus\Command\\' . ucfirst($taskConfig['command']) . 'Command';
                $cmd = new $cmdClass();
                $input = new ArrayInput(
                    array(
                        'mode' => $taskConfig['mode'],
                        'file' => $taskConfig['file'],
                    )
                );
                return $cmd->run($input, $output);
            });
            $tasks[] = $cmd;
        }

        return $tasks;
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
