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

.. warning::

    This MUST be in octal format, 755 and 0755 will yeild different results.

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

