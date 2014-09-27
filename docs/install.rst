============
Installation
============

Daedalus can easily be installed using `Composer <https://getcomposer.org>`_.

With Composer
=============

.. code-block:: bash

    php composer.phar require "daedalus/daedalus ~1.0@dev"

Global Install with Composer
============================

.. code-block:: bash

    php composer.phar global require "daedalus/daedalus ~1.0@dev"


.. note::

    You need to make sure that the global composer bin directory is in your
    PATH

Without Composer
================

It's not recommended that you work with Daedalus without the use of Composer
since the project takes advantage of Composer autoloading functionality.
If however you want to install Daedalus into a project without Composer you
will need to make sure you have the required dependencies which you can find
in the ``composer.json`` file and you will need to make sure you have an
autoloader setup. All of which is beyond the scope of this project.
