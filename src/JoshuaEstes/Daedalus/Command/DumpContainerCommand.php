<?php

namespace JoshuaEstes\Daedalus\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 */
class DumpContainerCommand extends Command
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('dump-container')
            ->setDescription('Dumps the container to display configuration information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = $this->container->getParameterBag()->all();
        $serviceIds = $this->container->getServiceIds();


        $this->renderParameterInformation($output);
        $output->writeln(array(''));
        $this->renderServiceInformation($output);
    }

    private function renderParameterInformation(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('Parameter Name', 'Parameter Value'));
        foreach ($this->container->getParameterBag()->all() as $name => $value) {
            $table->addRow(array($name, $value));
        }
        $table->render();
    }

    private function renderServiceInformation(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(array('Service ID', 'Class'));
        foreach ($this->container->getServiceIds() as $id) {
            $service = $this->container->get($id);
            $table->addRow(array($id, get_class($service)));
        }
        $table->render();
    }
}
