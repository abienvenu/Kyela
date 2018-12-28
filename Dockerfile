FROM php:7-apache

ARG COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y unzip libicu-dev vim \
	&& docker-php-ext-install intl \
	&& a2enmod rewrite \
	&& echo "memory_limit = -1" > /usr/local/etc/php/conf.d/php-memory.ini

# Install and configure Composer, PHPUnit and Symfony skeleton
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& curl -LsS https://phar.phpunit.de/phpunit-7.phar -o /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit \
	&& composer create-project symfony/skeleton /var/www/kyela 3.4

WORKDIR "/var/www/kyela"

# Install and configure Kyélà
COPY . src/Kyela
RUN ln -s ../components public/components \
	&& cp src/Kyela/Resources/public/favicon.ico public/favicon.ico \
	&& cp src/Kyela/docker/patches/kyela.conf /etc/apache2/sites-enabled/000-default.conf \
	&& cp src/Kyela/docker/patches/routing.yml config/routes.yaml \
	&& cp src/Kyela/docker/patches/services.yml config/services.yaml \
	&& cp src/Kyela/docker/patches/doctrine.yaml config/packages/doctrine.yaml \
	&& cp src/Kyela/behat.yml behat.yml \
	&& cp src/Kyela/phpunit.xml phpunit.xml \
	&& composer config repositories.kyela path /var/www/kyela/src/Kyela \
	&& composer require twig translation annotations \
		orm form validator templating monolog asset assetic-bundle \
		profiler symfony/browser-kit symfony/css-selector \
		abienvenu/kyela:@dev

# Deploy assets, create database, load example data and run tests
RUN bin/console assetic:dump \
	&& mkdir data \
	&& bin/console doctrine:schema:create \
	&& bin/console doctrine:fixtures:load --append \
	&& chown -R www-data.www-data data \
	&& bin/console cache:clear --env=test \
	&& phpunit \
	&& chown -R www-data.www-data var/cache \
	&& chown -R www-data.www-data var/log
