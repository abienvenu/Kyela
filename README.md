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

TODO
----

* Edit choices : improve the look & feel, add a colorpicker
* Edit Participant should let him edit his choices as well
* Avoid duplicate in routing.yml
* Make CSRF work (it is disabled in my main config.yml)
* See unit tests
* Avoid duplicate code in auto-generated CRUD controlers
* Add confirmation before deleting a Participant
* Use a cool date/time widget
* Multi-poll
* Create a default list of choices when creating a new poll
* Sort dates
* Archive old dates
* Access to old dates
* For a Poll, customize "Total" color cell at certain levels
* Add unicity constraint for (event, participant, choice)

BUGS
----

* None yet

