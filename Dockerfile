FROM php:7-apache

RUN apt-get update \
	&& apt-get install -y unzip libicu-dev patch vim \
	&& docker-php-ext-install intl \
	&& a2enmod rewrite \
	&& apache2ctl graceful

# Configure Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/kyela/web\nSetEnv SYMFONY__CONTACT__EMAIL ${CONTACT_EMAIL}|' /etc/apache2/sites-enabled/000-default.conf

# Install and configure Composer, PHPUnit and Symfony
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& curl -LsS https://phar.phpunit.de/phpunit-5.7.14.phar -o /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit \
	&& curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony && chmod a+x /usr/local/bin/symfony \
	&& symfony --ansi new /var/www/kyela 2.8

WORKDIR "/var/www/kyela"

# Install Kyélà
COPY . src/Abienvenu/KyelaBundle
RUN patch -p1 -i src/Abienvenu/KyelaBundle/docker/patches/composer.json.diff composer.json \
	&& composer require symfony/assetic-bundle doctrine/doctrine-fixtures-bundle twig/extensions robloach/component-installer \
		"components/jquery ^3.1" "components/jqueryui ^1.12" "components/bootstrap ^3.3" \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/config.yml app/config/config.yml \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/parameters.yml app/config/parameters.yml \
	&& patch -p1 -i src/Abienvenu/KyelaBundle/docker/patches/AppKernel.php.diff app/AppKernel.php \
	&& patch -p1 -i src/Abienvenu/KyelaBundle/docker/patches/app_dev.php.diff web/app_dev.php \
	&& cp src/Abienvenu/KyelaBundle/docker/patches/routing.yml app/config/routing.yml \
	&& composer remove incenteev/composer-parameter-handler \
	&& rm -rf src/AppBundle

# Deploy assets, create database, load example data and run tests
RUN app/console assets:install \
	&& app/console assetic:dump \
	&& mkdir data \
	&& app/console doctrine:schema:create \
	&& app/console doctrine:fixtures:load --append \
	&& chown -R www-data.www-data data \
	&& app/console cache:clear --env=test && phpunit -c app \
	&& chown -R www-data.www-data app/cache \
	&& chown -R www-data.www-data app/logs
