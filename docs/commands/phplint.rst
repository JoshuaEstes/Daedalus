=======
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

