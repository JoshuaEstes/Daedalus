<?php
/**
 */

namespace Daedalus;

use Daedalus\Helper\FormatterHelper;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class Application extends BaseApplication
{
    private $kernel;
    private $commandsRegistered = false;

    /**
     * Creates a new instance of the app
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct('Daedalus', Kernel::VERSION);

        /**
         * Update definition with new options
         */
        $this->getDefinition()->addOptions(
            array(
                new InputOption('buildfile', null, InputOption::VALUE_REQUIRED, 'build file'),
                new InputOption('propertyfile', null, InputOption::VALUE_REQUIRED, 'Properties file'),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot($this, $input, $output);
        $this->addOutputFormatterStyles($output);

        if (!$this->commandsRegistered) {
            $this->registerCommands();
            $this->commandsRegistered = true;
        }

        $container = $this->kernel->getContainer();

        $this->setDispatcher($container->get('event_dispatcher'));

        $returnCode = parent::doRun($input, $output);

        if (0 !== $returnCode && $this->getCommandName($input)) {
            $output->writeln(
                $this->getHelperSet()->get('formatter')->formatSection('build', '<error>failure</error>')
            );
        } elseif ($this->getCommandName($input)) {
            $output->writeln(
                $this->getHelperSet()->get('formatter')->formatSection('build', '<success>success</success>')
            );
        }

        return $returnCode;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        return array(
            new ListCommand(),
        );
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultHelperSet()
    {
        return new HelperSet(array(
            new FormatterHelper(),
            new DialogHelper(),
            new ProgressHelper(),
            new TableHelper(),
            new QuestionHelper(),
        ));
    }

    /**
     * Registers the commands that are displayed to the developer
     */
    protected function registerCommands()
    {
        $this->add(new \Daedalus\Command\DumpContainerCommand($this->kernel->getContainer()));
        $this->add(new \Daedalus\Command\HelpCommand($this->kernel->getContainer()));
    }

    /**
     * Adds extra styles to the output
     *
     * @param OutputInterface $output
     */
    protected function addOutputFormatterStyles(OutputInterface $output)
    {
        foreach ($this->getOutputFormatterStyles() as $name => $style) {
            $output->getFormatter()->setStyle($name, $style);
        }
    }

    /**
     * @return array
     */
    protected function getOutputFormatterStyles()
    {
        return array(
            'success' => new OutputFormatterStyle('white', 'green', array('bold')),
            'error'   => new OutputFormatterStyle('white', 'red', array('bold')),
        );
    }
}
