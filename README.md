joshuaestes/daedalus
====================

[![Documentation Status](https://readthedocs.org/projects/daedalus/badge/?version=latest)](https://readthedocs.org/projects/daedalus/?badge=latest)

[-] As a developer I want a PHP task runner that is able to use yml, xml, or php
files to configure a list of commands that can be ran.
* Able to use yml, also it should be trivial to add xml and php support

[x] As a developer I want the ability to group commands together as a task.

As a developer I want the ability to use a build properties files.

As a developer I want the ability to have access to environmental variables
that I can use with commands.

As a developer I want the ability to create a changelog file based on the
commit diff of now from the last tag.

As a developer I want the ability to manage Semantic Versioning.

Tasks
=====

A task is a named collection of one or more commands.

Commands
========

A command is in charge of one thing only such as deleting all files in a given
directory.

# ChmodCommand

As a developer I want the ability to chmod a file or files.

# ChownCommand

As a developer I want the ability to chown a file or files.

# CopyCommand

As a developer I want the ability to copy a file or files.

As a developer I want to copy a file from one directory to another and rename
that file.

# ExecCommand

As a developer I want to be able to execute an external program.

# LintCommand

As a developer I want to be able to lint check my php files.

# MkdirCommand

As a developer I want to ability to create a directory.

# MoveCommand

As a developer I want the ability to move a file.

As a developer I want the ability to move a directory.

# PharCommand

As a developer I want the ability to create a phar.

# TouchCommand

As a developer I want to be able to touch a file.

# VagrantSSHCommand

As a developer I want the ability to run a command on a VM.

# ZipCommand

As a developer I want the ability to create an archive file so that I can create
releases.

Installation
============

use composer

Usage
=====

  php bin/daedalus [task]

Creating a build script
=======================

see build.yml

Default Properties
==================

make table of properties

Commands
========

Make list of commands here

Request New Command
===================

@article

Getting Help
============

php bin/daedalus help [command]

How to Add Your Own Command
===========================

@tutorial

Automate Builds With This Tool
==============================

@article

Development/Hacking This Tool
=============================

@article

License
=======

@license
