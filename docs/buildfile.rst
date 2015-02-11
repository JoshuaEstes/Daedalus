================
Buildfile Format
================

Daedalus uses the concept of a Task and a Task runs multiple commands. See the
Commands section for an overview of the commands that you can use.

Basic Example
=============

.. code-block:: yaml

    # build.yml
    daedalus:
        tasks:
            <taskname>:
                description: <description>
                commands:
                    <command name>:
                        command: <command name>
                        arguments:
                            <argument>: <argument value>
                        options:
                            <option>: <option value>

.. note::

    Arguments are based on the command that you choose to run. For a list of
    arguments for the command, please refer to the Commands section.

The ``<taskname>`` can be replaced with any unique name that you choose. This
will also be the task that you run from the command line ie ``bin/daedalus <taskname>``.

The ``<description>`` is optional, but if you add one, it will be displayed when
the tasks are listed.

You can have as many commands as you want and they will be ran in the order in
which they are added.

Complete Basic Example
----------------------

.. code-block:: yaml

    # build.yml
    daedalus:
        tasks:
            chmod:
                description: Run chmod on a file
                commands:
                    chmod:
                        command: chmod
                        arguments:
                            mode: 0755
                            file: /path/to/file

Advanced Example
================

You can configure various settings of Daedalus to make the behaviour different.
Please refer to the Configuration section for a complete listing of these
settings.

Complete Advanced Example
-------------------------
