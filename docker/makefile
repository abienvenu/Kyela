# These are a few helpers to help run and develop Kyélà

build:
	docker build -t abienvenu/kyela ..

volume:
	docker volume create --name kyela-data

update-schema:
	docker exec kyela-git app/console doctrine:schema:update --dump-sql

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela-git abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -v $(shell pwd)/..:/var/www/kyela/src/Abienvenu/KyelaBundle -v /tmp/behat_screenshots:/tmp/behat_screenshots -d --name kyela-git abienvenu/kyela

get-vendor:
	docker cp kyela-git:/var/www/kyela/vendor ../vendor

test:
	docker exec kyela-git phpunit
	docker exec kyela-git vendor/bin/behat --colors

test-prep:
	docker exec kyela-git sh -c "COMPOSER_ALLOW_SUPERUSER=1 composer require --dev behat/behat behat/symfony2-extension behatch/contexts behat/mink-goutte-driver behat/mink-selenium2-driver"
	docker run -p 4444:4444 -d --name selenium selenium/standalone-chrome:3.8.1
