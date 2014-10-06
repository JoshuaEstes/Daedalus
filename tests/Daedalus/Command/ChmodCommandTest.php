<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

class ChmodCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->application = new Application();
        $this->root = vfsStream::setup('test');
    }

    public function testExecute()
    {
        $this->application->add(new ChmodCommand());
        $command       = $this->application->find('chmod');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command' => $command->getName(),
                '--mode'  => 0755,
                '--file'  => vfsStream::url('test'),
            )
        );
    }
}
