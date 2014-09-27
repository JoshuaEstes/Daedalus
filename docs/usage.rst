=====
Usage
=====

Once installed and your build file setup, you can simply run daedalus to get a
list of tasks you can run.

.. code-block:: bash

    vendor/bin/daedalus

Getting Help
============

If you ever need help for any of the commands, you can run the help command
which will return the help document on how to use that command. For example

.. code-block:: bash

    vendor/bin/daedalus help chmod

You can also get an idea of the properties you have access to and some other
information by running the ``dump-container`` task.

.. code-block:: bash

    $ vendor/bin/daedalus dump-container
    +----------------+---------------------------------------------------+
    | Parameter Name | Parameter Value                                   |
    +----------------+---------------------------------------------------+
    | env.editor     | vim                                               |
    | env.home       | /Users/joshuaestes                                |
    | env.lang       | en_US.UTF-8                                       |
    | env.pwd        | /Users/joshuaestes/Projects/joshuaestes/Daedalus  |
    | env.shell      | /bin/bash                                         |
    | env.tmpdir     | /var/folders/w_/vb3pk8dj5tq47b37n6nmzxbh0000gn/T/ |
    | env.user       | joshuaestes                                       |
    | php.version    | 5.4.33                                            |
    +----------------+---------------------------------------------------+

    +-------------------+--------------------------------------------------------+
    | Service ID        | Class                                                  |
    +-------------------+--------------------------------------------------------+
    | application       | Daedalus\Application                                   |
    | command.chgrp     | Daedalus\Command\ChgrpCommand                          |
    | command.chmod     | Daedalus\Command\ChmodCommand                          |
    | command.chown     | Daedalus\Command\ChownCommand                          |
    | command.copy      | Daedalus\Command\CopyCommand                           |
    | command.dump_file | Daedalus\Command\DumpFileCommand                       |
    | command.exec      | Daedalus\Command\ExecCommand                           |
    | command.help      | Daedalus\Command\HelpCommand                           |
    | command.mirror    | Daedalus\Command\MirrorCommand                         |
    | command.mkdir     | Daedalus\Command\MkdirCommand                          |
    | command.phar      | Daedalus\Command\PharCommand                           |
    | command.phplint   | Daedalus\Command\PhpLintCommand                        |
    | command.remove    | Daedalus\Command\RemoveCommand                         |
    | command.rename    | Daedalus\Command\RenameCommand                         |
    | command.symlink   | Daedalus\Command\SymlinkCommand                        |
    | command.touch     | Daedalus\Command\TouchCommand                          |
    | event_dispatcher  | Symfony\Component\EventDispatcher\EventDispatcher      |
    | filesystem        | Symfony\Component\Filesystem\Filesystem                |
    | finder            | Symfony\Component\Finder\Finder                        |
    | kernel            | Daedalus\Kernel                                        |
    | process_builder   | Symfony\Component\Process\ProcessBuilder               |
    | service_container | Symfony\Component\DependencyInjection\ContainerBuilder |
    | task.build        | Symfony\Component\Console\Command\Command              |
    | task.checkstyle   | Symfony\Component\Console\Command\Command              |
    | task.lint         | Symfony\Component\Console\Command\Command              |
    | task.phpunit      | Symfony\Component\Console\Command\Command              |
    +-------------------+--------------------------------------------------------+

As you can see there is a section of properties and a section of services. The
services are helpful if you ever want to develop or hack Daedalus.
