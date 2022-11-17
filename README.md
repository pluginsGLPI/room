Room Management plugin for [GLPI](www.glpi-project.org)
=======================================================

This is a plugin to add room management feature to [**GLPI** assets inventory
software](http://glpi-project.org).

Description
-----------

This plugin allows you to manage the rooms and the elements that are included
in. A room is not the same as a location that already exists in GLPI because it
can not contain items nor be loaned (which a room can be).

GLPI 9.5.1 compatibility added and tested. Translation fixed (gettext domain). 
I also added a basic hungarian translation :)

Installation
------------

This plugins installs as any other GLPI plugin.

1. Place the current source code tree in a directory named `room` and move this
   one inside the `plugins` directory of your GLPI installation.
2. Go to *Setup* > *Plugins*.
3. Look for the *Room Management* plugin's row.
4. Click on the *Install* button.
5. Click on the *Enable* button.

Uninstallation
--------------

This plugins uninstalls as any other GLPI plugin.

1. Go to *Setup* > *Plugins*.
2. Look for the *Room Management* plugin's row.
3. Click on the *Disable* button.
4. Click on the *Uninstall* button.
5. Delete the `plugins/room` directory of your GLPI installation.

Contributing
------------

Please follow the following rules for contributing:

* Respect [PSR-1](http://www.php-fig.org/psr/psr-1/) and [PSR-2](http://www.php-fig.org/psr/psr-2/).
* Open an issue for each bug/feature so it can be discussed.
* Follow [development guidelines](http://glpi-developer-documentation.readthedocs.io/en/master/plugins/guidelines.html).
* Refer to [GitFlow](http://git-flow.readthedocs.io) process for branching.
* Work on a new branch on your own fork.
* Open a PR for merging. It will be reviewed by a developer.

### Coding standards

Respect of coding standard is checked by [*PHP Coding Standards Fixer*](http://cs.sensiolabs.org).
The `.php_cs.dist` file contains the standards to conform to.

Command to list all PHP files with standard issues:

```Shell
php php-cs-fixer-v2.phar fix --config .php_cs.dist --dry-run.
```

Same command but includes detail of actual lines with standard issues:

```Shell
php php-cs-fixer-v2.phar fix --config .php_cs.dist --dry-run --diff .
```

Command to fix thoses issues:

```Shell
php php-cs-fixer-v2.phar fix --config .php_cs.dist .
```
