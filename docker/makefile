# These are a few helpers to help run and develop Kyélà

build:
	docker build --no-cache -t abienvenu/kyela ..

volume:
	docker volume create --name kyela-data

update-schema:
	docker exec kyela-git app/console doctrine:schema:update --dump-sql

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela -e APP_ENV=prod -e APP_SECRET=$(shell head /dev/urandom | tr -dc A-Za-z0-9 | head -c 20) abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v $(shell pwd)/..:/var/www/kyela/src/Kyela -v /tmp/behat_screenshots:/tmp/behat_screenshots -d --name kyela-git -e APP_ENV=dev -e APP_SECRET=$(shell head /dev/urandom | tr -dc A-Za-z0-9 | head -c 20) abienvenu/kyela

get-vendor:
	docker cp kyela-git:/var/www/kyela/vendor ../vendor

test:
	docker exec kyela-git bash -c "APP_ENV=test phpunit"
	docker start selenium
	docker exec kyela-git bash -c "APP_ENV=test vendor/bin/behat --colors"

test-prep:
	docker exec kyela-git sh -c "COMPOSER_ALLOW_SUPERUSER=1 composer require --dev behat/behat behat/symfony2-extension behatch/contexts behat/mink-goutte-driver behat/mink-selenium2-driver"
	docker run -p 4444:4444 -d --name selenium selenium/standalone-chrome:3.11
