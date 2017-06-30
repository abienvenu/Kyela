### Native installation

The prefered method to get your own Kyela server up and running is using Docker, like describe in the [README](README.md).
Now you can install Kyélà like in the good old days. This is quiet a longer way though...

* Install Symfony 2.8
* Install Composer
* In the folder where you installed Symfony, edit composer.json and add in the "config" section:
```
        "component-dir": "web/components"
```
* Download and install the Kyélà bundle :
```bash
$ composer require "abienvenu/kyela dev-master"
```
* Add the bundle and its depedencies in your AppKernel.php :
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Symfony\Bundle\AsseticBundle\AsseticBundle(),
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
* Configure your database parameters in app/config/parameters.yml
* Include the config from your app/config/config.yml :
```YAML
    imports:
        - { resource: "@KyelaBundle/Resources/config/config.yml" }
```
* Add Kyela to the bundles handled by assetic in app/config/config.yml :
```YAML
assetic:
    bundles:        [ 'KyelaBundle' ]
    filters:
        cssrewrite: ~
```
* Dump the assets :
```bash
$ app/console assetic:dump
```

#### Loading examples

Fixtures are available to automatically load examples (concert and picnic).
They are pre-loaded in the Docker image, but if you made a native install, you need to load them manually:

* Install DoctrineFixturesBundle :
```bash
$ composer require "doctrine/doctrine-fixtures-bundle ^2.2"
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
$ php app/console doctrine:schema:create
$ php app/console doctrine:fixtures:load --append
```

#### Loading examples

Fixtures are available to automatically load examples (concert and picnic).
They are pre-loaded in the Docker image, but if you made a native install, you need to load them manually:

* Install DoctrineFixturesBundle :
```bash
$ composer require "doctrine/doctrine-fixtures-bundle ^2.2"
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
$ php app/console doctrine:schema:create
$ php app/console doctrine:fixtures:load --append
```
