FROM dunglas/frankenphp:php8.4

ENV SERVER_NAME=:80

RUN apt-get update && apt-get install -y --no-install-recommends \
	gettext \
	&& rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
	;

COPY bin /app/bin
COPY public /app/public
COPY config /app/config
COPY templates /app/templates
COPY assets /app/assets
COPY translations /app/translations
COPY src /app/src

COPY importmap.php /app/importmap.php
COPY composer.lock /app/composer.lock
COPY composer.json /app/composer.json
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install
