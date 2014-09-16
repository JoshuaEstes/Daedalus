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
`%user.home`.
