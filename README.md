Kyela
=====

Participation polls for group events

Server Installation
-------------------

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

* Choices inside the grid (dropdownmenus ?)
* Edit choices : improve the look & feel
* Edit Participant should let him edit his choices as well
* Avoid duplicate in routing.yml
* Make CSRF work (it is disabled in my main config.yml)
* See unit tests
* Avoid duplicate code in auto-generated CRUD controlers
* Add confirmation before deleting a Participant
* Use a cool date/time widget

BUGS
----

* None yet

