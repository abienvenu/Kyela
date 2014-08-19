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
    debug:          "%kernel.debug%"
    bundles:        [ KyelaBundle ]
```

* Install the assets :
    app/console assets:install --symlink

TODO
----

* Make CSRF work
