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

lint
====

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
