<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 */
class HelpCommand extends BaseCommand
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $serviceId = sprintf('command.%s', $input->getArgument('command_name'));

        if (!$this->container->has($serviceId)) {
            throw new \Exception(
                sprintf('The command "%s" was not found.', $input->getArgument('command_name'))
            );
        }

        $this->setCommand($this->container->get($serviceId));
    }
}
