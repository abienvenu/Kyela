build:
	docker build -t abienvenu/kyela ..

volume:
	docker volume create --name kyela-data

update-schema:
	docker exec kyela app/console doctrine:schema:update --dump-sql

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela -e CONTACT_EMAIL=nobody@mailinator.com abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -v /var/www/kyela/src/Abienvenu/KyelaBundle:/var/www/kyela/src/Abienvenu/KyelaBundle -d --name kyela abienvenu/kyela

test:
	docker exec kyela phpunit -c app
