# These are a few helpers to help run and develop Kyélà

build:
	docker build -t abienvenu/kyela ..

volume:
	docker volume create --name kyela-data

update-schema:
	docker exec kyela-git app/console doctrine:schema:update --dump-sql

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela-git -e CONTACT_EMAIL=nobody@mailinator.com abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -v $(shell pwd)/..:/var/www/kyela/src/Abienvenu/KyelaBundle -d --name kyela-git abienvenu/kyela

get-vendor:
	docker cp kyela-git:/var/www/kyela/vendor ../vendor

test:
	docker exec kyela-git phpunit -c app
	docker exec kyela-git bin/behat
