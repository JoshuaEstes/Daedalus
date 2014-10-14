====
copy
====

Copy file from one location to another

Arguments
---------

src
^^^

Source location of file

dest
^^^^

Destination of where to put file

Options
-------

overwrite
^^^^^^^^^

If the file already exists, replace it.

Examples
--------

.. code-block:: yaml

    daedalus:
        task:
            copy_file:
                commands:
                    first_command:
                        command: copy
                        arguments:
                            src: /path/to/file.ext
                            dest: /path/to/copied/file.ext
                        options:
                            overwrite: true
