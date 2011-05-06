
DESCRIPTION
-----------

The Basic Meeting List Toolbox (BMLT) is a powerful, database-driven system for tracking NA meetings.
It is not an official product of NA ( http://na.org ). Rather, it is a project designed and implemented by
NA members, and meant to be used by official NA Service bodies.

This project is a Drupal module that implements a client, or "satellite." It will allow you to connect to
a central "root" server, and display meeting searches in a Drupal installation.

REQUIREMENTS
------------

The project requires a functioning BMLT root server ( http://magshare.org/blog/installing-the-root-server/ ).
It does not implement a root server, but connects to an existing one.
It requires PHP 5.0 or above.

INSTALLATION
------------

You install the 'bmlt-6.x-' or 'bmlt-7.x-' directory in /sites/all/modules/ (You may need to create the "modules"
directory).
Enable the module in the usual manner.
This module operates through the use of text filters/input formats. You need to set up a text filter that uses the
"Add a BMLT instance inline in text" filter.

USAGE
-----

EXAMPLES
--------
