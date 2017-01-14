build:
	docker build -t abienvenu/kyela .

volume:
	docker volume create --name kyela-data

run:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -d --name kyela abienvenu/kyela

run-dev:
	docker run -p 8042:80 -v kyela-data:/var/www/kyela/data -v /var/www/kyela/src/Abienvenu/KyelaBundle:/var/www/kyela/vendor/abienvenu/kyela -d --name kyela abienvenu/kyela

test:
	docker exec kyela phpunit -c app vendor/abienvenu/kyela/Tests/

