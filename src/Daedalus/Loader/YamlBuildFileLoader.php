<?php

namespace Daedalus\Loader;

use Daedalus\Kernel;
use Daedalus\Configuration\TaskConfiguration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class YamlBuildFileLoader extends FileLoader
{
    /**
     * @inheritdoc
     */
    public function load($resource, $type = null)
    {
        $path    = $this->locator->locate($resource);
        $content = $this->loadFile($path);
        $this->container->addResource(new FileResource($path));

        if (null === $content) {
            return;
        }

        if (isset($content['properties'])) {
            $this->processProperties($content['properties']);
        }

        if (isset($content['tasks'])) {
            $this->processTasks($content['tasks']);
        }
    }

    /**
     * @inheritdoc
     */
    public function supports($resource, $type = null)
    {
        if (Kernel::TYPE_BUILD_FILE !== $type) {
            return false;
        }

        if (!is_string($resource)) {
            return false;
        }

        $ext = pathinfo($resource, PATHINFO_EXTENSION);

        if ('yml' !== $ext && 'yaml' !== $ext) {
            return false;
        }

        return true;
    }

    /**
     * @param  string $path
     * @return array
     */
    protected function loadFile($path)
    {
        return $this->validate(Yaml::parse($path), $path);
    }

    /**
     * @param  string $content
     * @param  string $file
     * @return array
     */
    protected function validate($content, $file)
    {
        if (!is_array($content)) {
            throw new \Exception('Invalid YAML format, could not parse file');
        }

        $processor = $this->getProcessor();
        $content   = $processor->processConfiguration(
            new TaskConfiguration(),
            $content
        );

        return $content;
    }

    /**
     * @return Processor
     */
    protected function getProcessor()
    {
        return new Processor();
    }

    /**
     * Process properties that need to be injected into the container
     *
     * @param array $properties
     */
    protected function processProperties($properties)
    {
    }

    /**
     * Process tasks that the user has defined and turn them into commands
     * that can be ran.
     *
     * @param array $tasks
     */
    protected function processTasks($tasks)
    {
        foreach ($tasks as $taskName => $taskConfig) {
            $serviceId = sprintf('task.%s', $taskName);
            $this->container->setDefinition(
                $serviceId,
                new Definition()
            )->setSynthetic(true);

            $command = $this->buildTask($taskName, $taskConfig);

            $this->container->set($serviceId, $command);
            $this->container->get('application')->add($command);
        }
    }

    /**
     */
    protected function buildTask($name, $config)
    {
        $container = $this->container;
        $command   = new Command($name);
        $command->setDescription($config['description']);
        $command->setCode(function (InputInterface $input, OutputInterface $output) use ($config, $container) {
            foreach ($config['requires'] as $task) {
                $service = $container->get(sprintf('task.'.$task));
                $service->run($input, $output);
            }
            foreach ($config['commands'] as $cmd => $cmdConfig) {
                $output->writeln('Starting '.$cmd);
                $serviceId = sprintf('command.%s', $cmdConfig['command']);
                if (!$container->has($serviceId)) {
                    $output->writeln('Command not found');
                    continue;
                }

                $service       = $container->get($serviceId);
                $serviceConfig = array();

                foreach ($cmdConfig['arguments'] as $arg => $value) {
                    $serviceConfig[$arg] = $value;
                }

                foreach ($cmdConfig['options'] as $opt => $value) {
                    $serviceConfig['--'.$opt] = $value;
                }

                $output->writeln('Running Command '.$service->getName());
                $code = $service->run(new ArrayInput($serviceConfig), $output);
                $output->writeln('Command '.$service->getName().' Complete');

                if (0 !== $code) {
                    $output->writeln('Command FAIL');
                }
            }
        });

        return $command;
    }
}
