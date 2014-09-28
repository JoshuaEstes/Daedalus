<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

class ChgrpCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->application = new Application();
        $this->root = vfsStream::setup('test');
    }

    public function testExecute()
    {
        $this->application->add(new ChgrpCommand());
        $command = $this->application->find('chgrp');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'command'   => $command->getName(),
                '--file'      => vfsStream::url('test'),
                '--group'     => vfsStream::GROUP_ROOT,
                '--recursive' => true,
            )
        );
    }
}
