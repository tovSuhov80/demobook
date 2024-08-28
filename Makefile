DOCKER_COMPOSE?=docker-compose
RUN=$(DOCKER_COMPOSE) run --rm php
EXEC?=docker exec -it demobook-php
COMPOSER=$(EXEC) composer
DB_WAIT=$(EXEC) php -r "echo \"Waiting for db...\n\";sleep(5);"

install: start wait-for-db composer-install
	cp .env.example .env
	$(RUN) yii migrate/up --interactive=0


composer-install:
	$(COMPOSER) install -n

composer-update:
	$(COMPOSER) update

start:
	$(DOCKER_COMPOSE) up -d

stop:
	$(DOCKER_COMPOSE) stop

docker-compose-clear:
	$(DOCKER_COMPOSE) down --rmi all

migrate:
	$(RUN) yii migrate

migrate-down:
	$(RUN) yii migrate/down

migrate-down-all:
	$(RUN) yii migrate/down 999999 --interactive=0

bash:
	$(EXEC) bash

wait-for-db:
	$(DB_WAIT)


clear:
	sudo rm -rf runtime/*
	sudo rm -rf web/assets/*

perm: clear
	sudo chmod -R 777 runtime
	sudo chmod -R 666 migrations/*
	sudo chmod 777 migrations

##Makefile.local
-include Makefile.local