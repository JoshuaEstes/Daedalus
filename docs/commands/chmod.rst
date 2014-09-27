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

