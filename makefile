.DEFAULT_GOAL := help

.PHONY: help ## list makefile targets
help:
		@grep '##' makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t31

.PHONY: build ## build the docker image
build:
	docker build -t abienvenu/kyela2 .

.PHONY: start ## run the application in dev mode
start:
	docker run --name kyela2 --restart unless-stopped -d -v ${PWD}:/app -p 12880:80 abienvenu/kyela2
	docker exec -it kyela2 composer install
	@echo "Point your browser to http://localhost:12880/participation"

.PHONY: start-prod ## run the application in prod mode
start-prod:
	docker run --name kyela2 --restart unless-stopped -d --env APP_ENV=prod -p 12880:80 abienvenu/kyela2
	@echo "Point your browser to http://localhost:12880/participation"

.PHONY: stop ## stop the application
stop:
	@docker stop kyela2

.PHONY: down ## stop and delete the application
down: stop
	@docker rm kyela2

.PHONY: enter ## get a shell into the application container
enter:
	docker exec -it kyela2 bash

.PHONY: dbconnect ## get a database connection
dbconnect:
	docker exec -it kyela2 sh -c "apt-get update && apt-get install sqlite3 && sqlite3 var/data.db"

.PHONY: getvendor ## copy the vendor directory to the host (useful for dev)
getvendor:
	rm -rf vendor
	docker cp kyela2:/app/vendor .
