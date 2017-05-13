# These are a few helpers to help run and develop Kyélà

build:
	docker build -t abienvenu/kyela ..

volume:
	docker volume create --name kyela-data

update-schema:
	docker exec kyela app/console doctrine:schema:update --dump-sql

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela -e CONTACT_EMAIL=nobody@mailinator.com abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -v $(shell pwd)/..:/var/www/kyela/src/Abienvenu/KyelaBundle -d --name kyela abienvenu/kyela

get-vendor:
	docker cp kyela:/var/www/kyela/vendor ../vendor

test:
	docker exec kyela phpunit -c app
