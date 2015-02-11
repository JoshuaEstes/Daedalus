<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

class ChownCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->application = new Application();
        $this->root = vfsStream::setup('file.ext');
    }

    public function testExecuteSuccess()
    {
        $this->application->add(new ChownCommand());
        $command       = $this->application->find('chown');
        $commandTester = new CommandTester($command);
        $exitCode      = $commandTester->execute(
            array(
                'command'     => $command->getName(),
                '--file'      => vfsStream::url('file.ext'),
                '--user'      => vfsStream::GROUP_ROOT,
                '--recursive' => true,
            )
        );
        $this->assertSame(0, $exitCode);
    }
}
