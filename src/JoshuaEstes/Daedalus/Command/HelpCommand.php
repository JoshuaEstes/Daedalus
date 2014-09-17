<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 */
class HelpCommand extends Command
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
    protected function configure()
    {
        $this
            ->setName('help')
            ->setDescription('Displays help for a command')
            ->setDefinition(
                array(
                    new InputArgument('command_name', InputArgument::REQUIRED, 'The command name'),
                )
            )
            ->setHelp(
<<<HELP

Help blah

HELP
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $serviceId = sprintf('command.%s', $input->getArgument('command_name'));

        if (!$this->container->has($serviceId)) {
            throw new \Exception(
                sprintf('The command "%s" was not found.', $input->getArgument('command_name'))
            );
        }

        $command = $this->container->get($serviceId);
        $helper = new DescriptorHelper();
        $helper->describe(
            $output,
            $command
        );
    }
}
