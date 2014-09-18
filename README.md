Kyela
=====
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/abienvenu/Kyela/badges/build.png?b=master)](https://scrutinizer-ci.com/g/abienvenu/Kyela/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/abienvenu/kyela/v/stable.svg)](https://packagist.org/packages/abienvenu/kyela)
[![Total Downloads](https://poser.pugx.org/abienvenu/kyela/downloads.svg)](https://packagist.org/packages/abienvenu/kyela)
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

Customisation
-------------

You can create a file in Resources/translations called "faq-me.en.html" or "about-me.en.html" to add questions/answers to the FAQ or About page. See "faq.en.html" for the structure to be used.

For further customisation, you have to edit the code. Because of the licence (GNU Affero GPL), you must publish the modified code as soon as your project is online.

CHANGELOG
---------

* v0.6 : Added fixtures and images for examples
* v0.5 : Added ability to reorder choices
* v0.4 : Added ability to switch language English/French
* v0.3.2 : Added time widget
* v0.3 : Added deletion confirmation, autofocus, fixed poll deletion
* v0.2 : Added contact page, default URL and choices for new polls
* v0.1 : First (mostly) working release

TODO
----
* Remove customized generateUrl(), it breaks things
* Translate About
* Make a distinction between required fields and optional fields
* Error message presentation
* l18n for error messages
* Nice handling of bad (forged) URLs
* Functional tests
* Enable tests in scrutinizer-ci.com
* Access to old dates
* Ability to add MOTD below and/or above the table
* Add unicity constraint for (event, participant, choice)
* Ability to use a glyphicon(s) for each choice
* formnovalidate is not valid HTML ?
* A github page that links to kyela.net
* Comments/Forum
* Make a knpbundles readme


BUGS
----

* No known yet

FUTURE (MAY BE) FEATURES
------------------------

* For a Poll, customize "Total" color cell at certain thresholds
* Notification subscriptions
* Syndication
* Easy integration from remote websites
* Aggregate/Anonymous mode, for events with lots of participants: the grid only displays total numbers, you can add yourself, then you get a personal link to modify/delete your participation
* Integration with personal agendas (Google, Yahoo...)
* Ability to restrict access to some admin buttons (edit choices, edit poll...) with a pass phrase
* Make a logo, a favicon
* AJAX calls to avoid page reload when updating participation
* Integrate to Travis-CI

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/big.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)
[![knpbundles.com](http://knpbundles.com/abienvenu/Kyela/badge)](http://knpbundles.com/abienvenu/Kyela)

