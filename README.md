Kyela
=====
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/build.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/abienvenu/kyela/v/stable.svg)](https://packagist.org/packages/abienvenu/kyela)
[![Latest Unstable Version](https://poser.pugx.org/abienvenu/kyela/v/unstable.svg)](https://packagist.org/packages/abienvenu/kyela)
[![License](https://poser.pugx.org/abienvenu/kyela/license.svg)](https://packagist.org/packages/abienvenu/kyela)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/mini.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)

Participation polls for group events

Features
--------

* Create one or many polls
* Each poll has a randomized URL; only people who get the link can have access
* Suggest one or many dates
* Customize the choices (text and color), add more choices
* User comments
* Simple usage, no authentication
* Mobile friendly
* No ad, no fee, no spying, just OpenSource

Basic Usage
-----------

* Point your browser to a website hosting the Kyela application, like http://kyela.net
* Create a new poll
* Add participants and events
* Update availabilities
* Enjoy!

Server Installation
-------------------

If you want to install and run Kyela on your own server :

* Install Symfony 2.5
* Download the bundle :
```bash
$ php composer.phar require "abienvenu/kyela":"dev-master"
```
* Add the bundle in your AppKernel.php :
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Abienvenu\KyelaBundle\KyelaBundle(),
    );
}
```
* Include the route from your app/config/routing.yml :
```YAML
kyela:
    resource: "@KyelaBundle/Resources/config/routing.yml"
    prefix: /kyela
```
* Include the config from your app/config.config.yml :
```YAML
    imports:
	- { resource: "@KyelaBundle/Resources/config/config.yml" }
```
* Add Kyela to the bundles handled by assetic in app/config/config.yml :
```YAML
assetic:
    bundles:        [ 'KyelaBundle' ]
```
* In the Resources/config of the KyelaBundle directory, copy parameters.yml.dist to parameters.yml, and customize it
* Dump the assets :
```bash
$ app/console assetic:dump
```
* Install fonts :

Bootstrap glyphicons needs fonts, which cannot be handled properly by assetic.
Install the assets :
```bash
$ app/console assets:install --symlink
```
Add some rewritule to your apache configuration :

    RewriteEngine On
    RewriteRule ^/kyela/web/app_dev.php/fonts/(.*) /kyela/web/bundles/kyela/fonts/$1 [L]

Loading examples
----------------

Fixtures are available to automatically load examples (concert and picnic).

* Install DoctrineFixturesBundle :
```bash
$ php composer.phar require "doctrine/doctrine-fixtures-bundle": "2.2.*"
```
* Register the bundle :
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
    );
}
```
* Load the fixtures :
```bash
$ php app/console doctrine:fixtures:load --append
```

Customisation
-------------

To edit or extend the FAQ or About page, you just have to add entries in Resources/translations/faq.en.yml (or about.en.yml).

For further customisation, you have to edit the templates or the code. Because of the licence (GNU Affero GPL-3.0), you must publish the modified code as soon as your project is online.

CHANGELOG
---------
* v1.4.2 :
  - Fix : do not display "add comment" if there is no participant
* v1.4.1 :
  - Added protection against dumb crawlers
  - Explained cookie policy
* v1.4 :
  - Content enhancements on Homepage/Faq/About
  - New example: holidays
  - Ability to add dates without specifying name nor time
* v1.3 :
  - Added placeholders
  - Date and time are now optional
* v1.2 :
  - Added ability to add an icon for each choice
  - Removed the idea of a separate file about-me.en.yml and faq-me.en.yml - just edit the files
* v1.1 : Many small improvements
  - Ability to add a participant directly from poll view
  - Hide Choice priority, this is purely internal data
  - Better ergonomy for choice reordering
  - Fixed the bug when creating a Poll/Comment/Participant/Event with only spaces
  - Fixed choice ordering in poll view
* v1.0 : Added ability to add comments
* v0.9 : Added ability to lock a poll
* v0.8 : Added ability to add custom HTML above and below the poll
* v0.7.1 : Critical fix for creating choices
* v0.7 : Added access to past events
* v0.6 : Added fixtures and images for examples
* v0.5 : Added ability to reorder choices
* v0.4 : Added ability to switch language English/French
* v0.3.2 : Added time widget
* v0.3 : Added deletion confirmation, autofocus, fixed poll deletion
* v0.2 : Added contact page, default URL and choices for new polls
* v0.1 : First (mostly) working release

TODO
----
* Timezones ?
* Try to host Kyela on Amazon EC2
* Sort participants alphabetically ?
* Make a knpbundles readme
* Code improvements (see Scrutinizer)

BUGS
----

* No known yet

FUTURE (MAY BE) FEATURES
------------------------

* Descriptive placeholders
* Put Glyphicons on every button, including standard "Save", "Cancel"...
* For a Poll, customize "Total" color cell at certain thresholds
* Notification subscriptions
* Syndication
* Easy integration from remote websites
* Aggregate/Anonymous mode, for events with lots of participants: the grid only displays total numbers, you can add yourself, then you get a personal link to modify/delete your participation
* Integration with personal agendas (Google, Yahoo...)
* Make a logo, a decent favicon
* AJAX calls to avoid page reload when updating participation
* Integrate to Travis-CI

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/big.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)
[![knpbundles.com](http://knpbundles.com/abienvenu/Kyela/badge)](http://knpbundles.com/abienvenu/Kyela)

