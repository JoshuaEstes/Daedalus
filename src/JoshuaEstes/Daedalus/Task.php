<?php

namespace JoshuaEstes\Daedalus;

use Symfony\Component\Console\Command\Command;

class Task
{
    protected $name;
    protected $description;
    protected $commands = array();

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function addCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }
}
