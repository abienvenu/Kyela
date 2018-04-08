FROM php:7-apache

RUN apt-get update \
	&& apt-get install -y unzip libicu-dev patch vim \
	&& docker-php-ext-install intl \
	&& a2enmod rewrite \
	&& apache2ctl graceful

# Configure Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/kyela/web|' /etc/apache2/sites-enabled/000-default.conf

# Install and configure Composer, PHPUnit and Symfony
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& curl -LsS https://phar.phpunit.de/phpunit-7.phar -o /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit \
	&& curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony && chmod a+x /usr/local/bin/symfony \
	&& symfony --ansi new /var/www/kyela 3.4

WORKDIR "/var/www/kyela"

# Install Kyélà
COPY . src/Abienvenu/KyelaBundle
COPY Resources/public/favicon.ico web/favicon.ico
RUN COMPOSER_ALLOW_SUPERUSER=1 composer remove incenteev/composer-parameter-handler \
	&& patch -p1 -i src/Abienvenu/KyelaBundle/docker/patches/AppKernel.php.diff app/AppKernel.php \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/config.yml app/config/config.yml \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/parameters.yml app/config/parameters.yml \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/routing.yml app/config/routing.yml \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/services.yml app/config/services.yml \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/behat.yml behat.yml \
	&& cp phpunit.xml.dist phpunit.xml \
    && sed -i "s|<directory>tests</directory>|<directory>src/*/*Bundle/Tests</directory>|" phpunit.xml \
	&& patch -p1 -i src/Abienvenu/KyelaBundle/docker/patches/composer.json.diff composer.json \
	&& composer require symfony/assetic-bundle "doctrine/doctrine-fixtures-bundle ~2.2" twig/extensions \
	    robloach/component-installer "components/jquery ^3.1" "components/jqueryui ^1.12" "components/bootstrap ^3.3" \
	&& sed -i "s/'127.0.0.1', '::1'/'127.0.0.1', '172.17.0.1', '::1'/" web/app_dev.php \
	&& rm -rf src/AppBundle tests/AppBundle

# Deploy assets, create database, load example data and run tests
RUN bin/console assets:install \
	&& bin/console assetic:dump \
	&& mkdir data \
	&& bin/console doctrine:schema:create \
	&& bin/console doctrine:fixtures:load --append \
	&& chown -R www-data.www-data data \
	&& bin/console cache:clear --env=test \
	&& phpunit \
	&& chown -R www-data.www-data var/cache \
	&& chown -R www-data.www-data var/logs
