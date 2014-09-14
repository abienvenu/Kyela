Kyela
=====

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
    git clone https://github.com/abienvenu/Kyela.git

* Add the bundle in your AppKernel.php :
```php
new Abienvenu\KyelaBundle\KyelaBundle(),
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
    app/console assetic:dump

* Install fonts :

Bootstrap glyphicons needs fonts, which cannot be handled properly by assetic.
Install the assets :

    app/console assets:install --symlink

Add some rewritule to your apache configuration :

    RewriteEngine On
    RewriteRule ^/kyela/web/app_dev.php/fonts/(.*) /kyela/web/bundles/kyela/fonts/$1 [L]

Customisation
-------------

You can create a file in Resources/translations called "faq-me.en.html" or "about-me.en.html" to add questions/answers to the FAQ or About page. See "faq.en.html" for the structure to be used.

For further customisation, you have to edit the code. Because of the licence (Affero GPL), you must publish the modified code as soon as your project is online.

CHANGELOG
---------

* v0.4 : Added ability to switch language English/French
* v0.3.2 : Added time widget
* v0.3 : Added deletion confirmation, autofocus, fixed poll deletion
* v0.2 : Added contact page, default URL and choices for new polls
* v0.1 : First (mostly) working release

TODO
----
* Error message presentation
* l18n for error messages
* Nice handling of bad (forged) URLs
* Functional tests
* Archive old dates
* Access to old dates
* Add instructions how to download the product via composer
* For a Poll, customize "Total" color cell at certain levels
* Add unicity constraint for (event, participant, choice)
* Prevent special urls (like "faq" or "contact") to be allowed for poll urls
* Ability to re-order choices
* Ability to use a glyphicon(s) for each choice
* formnovalidate is not valid HTML ?
* Comments/Forum
* Optim : Get all participations in a single DQL join

BUGS
----

* When you click "FAQ", "About" or "Contact" from a poll, the menu won't link back to the poll

FUTURE (MAY BE) FEATURES
------------------------

* Notification subscriptions
* Syndication
* Easy integration from remote websites
* Aggregate/Anonymous mode, for events with lots of participants: the grid only displays total numbers, you can add yourself, then you get a personal link to modify/delete your participation
* Integration with personal agendas (Google, Yahoo...)
* Ability to restrict access to some admin buttons (edit choices, edit poll...) with a pass phrase
* Make a logo, a favicon
* AJAX calls to avoid page reload when updating participation

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/big.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)
