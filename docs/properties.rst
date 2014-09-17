======================
Properties File Format
======================

You use properties files in case you want to have a different build based on
where you are running the tool at. For example, you want want to have a few
different parameters when running a build on Travis CI than you would your
local development machine.

Default Properties
==================

========= =====
Property  Value
========= =====
user.home $HOME
========= =====

Usage
=====

In your build file, put the property between a percentage sign. For example
``%user.home%``.

Comments
========

Any line that starts with ``#`` is considered a comment.

Environment Variables
=====================


Example Properties File
=======================

.. code-block:: text

    # All properties files must have the extension .properties or they
    # will not load.
    # Empty lines and lines starting with "#" are not processed

    property_name=property

    # You can also use other properties
    build_dir=%user.home%/build
