<?php

namespace Daedalus\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

class CopyCommandTest extends \PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->application = new Application();
        $this->root = vfsStream::setup('root', null, array('file.ext' => 'Content'));
    }

    public function testExecuteSuccess()
    {
        $this->application->add(new CopyCommand());
        $command       = $this->application->find('copy');
        $commandTester = new CommandTester($command);
        $exitCode      = $commandTester->execute(
            array(
                'command'     => $command->getName(),
                'src'         => vfsStream::url('root/file.ext'),
                'dest'        => vfsStream::url('root/copy_of_file.ext'),
                '--overwrite' => true,
            )
        );

        $this->assertSame(0, $exitCode);
    }
}
