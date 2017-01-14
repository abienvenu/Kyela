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
	&& curl -LsS https://phar.phpunit.de/phpunit.phar -o /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit \
	&& curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony && chmod a+x /usr/local/bin/symfony \
	&& symfony --ansi new /var/www/kyela 2.8

WORKDIR "/var/www/kyela"

# Install Kyélà
RUN	composer require abienvenu/kyela \
	&& cp vendor/abienvenu/kyela/Resources/config/parameters.yml.dist vendor/abienvenu/kyela/Resources/config/parameters.yml \
	&& composer remove incenteev/composer-parameter-handler

COPY patches patches

RUN rm -rf /var/www/kyela/src \
	&& patch -p1 -i patches/config.yml.diff app/config/config.yml \
	&& patch -p1 -i patches/parameters.yml.diff app/config/parameters.yml \
	&& patch -p1 -i patches/AppKernel.php.diff app/AppKernel.php \
	&& patch -p1 -i patches/app_dev.php.diff web/app_dev.php \
	&& patch -p1 -i patches/composer.json.diff composer.json \
	&& cp patches/routing.yml app/config/routing.yml \
	&& rm -rf patches

RUN app/console assets:install \
	&& app/console assetic:dump

RUN mkdir data \
	&& app/console doctrine:schema:create \
	&& app/console doctrine:fixtures:load --append \
	&& chown -R www-data.www-data data \
	&& chown -R www-data.www-data app/cache \
	&& chown -R www-data.www-data app/logs
