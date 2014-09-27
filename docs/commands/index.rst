.. Definition of Done for Command Documentation
    * MUST have a description
    * MUST have a list of arguments
    * MUST describe what each argument does
    * MUST include at least one example

========
Commands
========

Commands are ran inside of a task.

chgrp
=====

chmod
=====

Allows you to chmod a file or directory.

Arguments
---------

file
^^^^

This is either the full path to a file or a directory.

mode
^^^^

Mode which you want to set.

Example Usage
-------------

.. code-block:: yaml

    daedalus:
        tasks:
            chmod:
                commands:
                    chmod_da_file:
                        command: chmod
                        arguments:
                            file: %user.home%/.daedalus/
                            mode: 0744

chown
=====

copy
====

dump_file
=========

exec
====

phplint
=======

Runs lint checking on PHP Files.

Arguments
---------

source
^^^^^^

This can be a list of files or directories.

Examples
--------

Basic example that shows how to use the basic functionality.

.. code-block:: yaml

    daedalus:
        tasks:
            lint_ya_files:
                commands:
                    linting_example:
                        command: phplint
                        arguments:
                            source: 'src/'

In this example it shows that you can use an array of sources.

.. code-block:: yaml

    daedalus:
        tasks:
            lint_ya_files:
                commands:
                    linting_example:
                        command: phplint
                        arguments:
                            source: ['src/', 'lib/']

mirror
======

mkdir
=====

phar
====

remove
======

rename
======

symlink
=======

touch
=====
