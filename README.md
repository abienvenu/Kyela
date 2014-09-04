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

Server Installation
-------------------

If you want to run Kyela on your server :

* Install Symfony 2.5
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
	- { resource: "@KyelaBundle/Resources/config/config.yml" }

* Add Kyela to the bundles handled by assetic in app/config/config.yml :
```YAML
assetic:
    bundles:        [ 'KyelaBundle' ]
```

* Dump the assets :
    app/console assetic:dump

* Install fonts :
Bootstrap glyphicons needs fonts, which cannot be handled properly by assetic.
Install the assets :
    app/console assets:install --symlink
Add some rewritule to your apache configuration :
    RewriteEngine On
    RewriteRule ^/kyela/web/app_dev.php/fonts/(.*) /kyela/web/bundles/kyela/fonts/$1 [L]

TODO
----

* Fusionner newAction avec createAction dans les contrôleurs ? editAction avec updateAction ?
* Séparer le ControllerTrait en deux ou trois (create/new - edit/update - delete) ?
* Ajouter getPoll() en abstract sur Entity.php ?
* Dans AbstractController, override generateUrl pour lui rajouter la pollUrl ?
* Homepage + FAQ
* Multi-poll
* Nice handling of bad (forged) URLs
* Ability to switch language
* Edit choices : improve the look & feel, add a colorpicker
* Edit Participant should let him edit his choices as well
* Avoid duplicate in routing.yml
* i18n for fields in participant_edit
* Make CSRF work (it is disabled in my main config.yml)
* Add confirmation before deleting a Participant
* Functional test for Participation
* Use a cool date/time widget
* Create a default list of choices when creating a new poll
* Sort dates
* Archive old dates
* Access to old dates
* For a Poll, customize "Total" color cell at certain levels
* Add unicity constraint for (event, participant, choice)
* Ability to re-order choices
* Ability to use a glyphicon for each choice
* Comments/Forum

BUGS
----

* Deleting an event does not work if not empty
* Clash if lowercase(url1) = lowercase(url2) ?

FUTURE FEATURES
---------------

* Notification subscriptions
* Syndication
* Easy integration from remote websites
* Aggregate/Anonymous mode, for events with lots of participants: the grid only displays total numbers, you can add yourself, then you get a personal link to modify/delete your participation

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6/big.png)](https://insight.sensiolabs.com/projects/bca46a72-4438-47e7-b629-4b9926e802a6)
