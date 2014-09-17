<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Command used to debug and find out information about the container object
 * itself.
 */
class DumpContainerCommand extends Command
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
            ->setName('dump-container')
            ->setDescription('Dumps the container to display configuration information');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->renderParameterInformation($output);
        $output->writeln(array(''));
        $this->renderServiceInformation($output);
    }

    /**
     * Renders a table that includes all parameters that a developer can use
     * in a build file
     *
     * @param OutputInterface $output
     */
    private function renderParameterInformation(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('Parameter Name', 'Parameter Value'));

        $parameters = $this->container->getParameterBag()->all();
        ksort($parameters);

        foreach ($parameters as $name => $value) {
            $table->addRow(array($name, $value));
        }
        $table->render();
    }

    /**
     * Renders a table that has all the services in the container
     *
     * @param OutputInterface $output
     */
    private function renderServiceInformation(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('Service ID', 'Class'));

        $serviceIds = $this->container->getServiceIds();
        sort($serviceIds);

        foreach ($serviceIds as $id) {
            $service = $this->container->get($id);
            $table->addRow(array($id, get_class($service)));
        }
        $table->render();
    }
}
