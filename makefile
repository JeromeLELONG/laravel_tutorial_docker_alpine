.PHONY: default install help logs

.DEFAULT_GOAL := help

USER_ID = $(shell id -u)
GROUP_ID = $(shell id -g)
PWD = $(shell pwd)

export UID = $(USER_ID)
export GID = $(GROUP_ID)
export LARAVEL_TUTORIAL_VERSION = $(shell node -p "require('./src/composer.json').version")

DOCKER_COMPOSE = docker-compose -f docker-compose.yml
DOCKER_COMPOSE_E2E = docker-compose -f docker-compose.test.yml

ifeq "${CI}" "true"
	DOCKER_COMPOSE_RUN = $(TTY) docker-compose -f docker-compose.test.yml run --rm --no-deps -T
	DOCKER_COMPOSE_E2E_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps -T
	DOCKER_COMPOSE_INT_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps -T
	DOCKER_COMPOSE_PROD_RUN = $(TTY) docker-compose -f docker-compose.prod.yml run --rm --no-deps -T
else
	DOCKER_COMPOSE_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps
	DOCKER_COMPOSE_E2E_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps
	DOCKER_COMPOSE_INT_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps
	DOCKER_COMPOSE_PROD_RUN = docker-compose -f docker-compose.test.yml run --rm --no-deps
endif

start: ## Start the client and server in docker for local development
	docker-compose up --build -d

start-prod: ## Start the client and server in docker for local development
	docker-compose -f docker-compose.prod.yml up  --build -d

start-alpine: ## Start the client and server in docker for local development
	docker-compose -f docker-compose.alpine.yml up  --build -d

stop: 
	docker-compose down --remove-orphans

logs: ## Display logs for all services
	$(DOCKER_COMPOSE) logs -f

logs-apache: ## Display logs for apache
	$(DOCKER_COMPOSE) logs -f apache

logs-mysql: ## Display logs for mysql
	$(DOCKER_COMPOSE) logs -f mysql

logs-node: ## Display logs for node
	$(DOCKER_COMPOSE) logs -f node

delete-mysql:
	-$(MAKE) stop
	docker volume rm laravel_tutorial_dbdata

show-current-version:
	echo $(LARAVEL_TUTORIAL_VERSION)

delete-images:
	docker rmi laravel_tutorial_docker_alpine_apache:latest
	docker rmi laravel_tutorial_docker_alpine_mysql:latest
	docker rmi laravel_tutorial_docker_alpine_ldap:latest

install: install-php install-e2e

install-php: 
	docker run -t --volume ${PWD}/src/:/usr/src  php:8.0-apache bash -c 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/src --filename=composer.phar && apt-get -yq update && apt-get install -y git && apt-get install -y zip unzip && cd /usr/src && php /usr/src/composer.phar install --ignore-platform-reqs --no-interaction' 

install-zend: 
	docker run -t --volume ${PWD}/src/siscolidentifiant/:/usr/src  php:5.6-apache bash -c 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/src --filename=composer.phar && apt-get -yq update && apt-get install -y git && apt-get install -y zip unzip && cd /usr/src && php /usr/src/composer.phar install --ignore-platform-reqs --no-interaction' 

install-laravel-framework: 
	docker run -t --volume ${PWD}/src/laravel_project/new-project/:/usr/src  php:8.0-apache bash -c 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/src --filename=composer.phar && apt-get -yq update && apt-get install -y git && apt-get install -y zip unzip && cd /usr/src && php /usr/src/composer.phar install --ignore-platform-reqs --no-interaction' 

install-e2e:
	docker run -t --volume ${PWD}/e2e/:/usr/src node:16-alpine ash -ci 'cd /usr/src && yarn install'

build:  ## Build the application container image.
	docker build -t laravel_tutorial --no-cache --force-rm .


build-alpine:  ## Build the application container image (alpine).
	docker build -t laravel_tutorial -f ./Dockerfile-alpine_build --no-cache --force-rm .

test-build-if-needed: ## Build the docker test image
	@if [ "$(build)" != "false" ]; then \
		echo 'Building application (call "make build=false test-e2e" to skip the build)...'; \
		${MAKE} build; \
	fi

test-e2e: test-build-if-needed ## e2e tests the builded application
	($(MAKE) test-e2e-start && $(MAKE) test-e2e-stop) || ($(MAKE) test-e2e-stop && exit 1)

test-e2e-start: ## start e2e tests in already running container
	NODE_ENV=test $(DOCKER_COMPOSE_E2E) up -d
	$(DOCKER_COMPOSE_E2E_RUN) e2e yarn cypress:install
	sleep 10 # by security

ifeq ($(CI), true)
	NODE_ENV=test $(DOCKER_COMPOSE_E2E) exec -T e2e yarn test
else
	NODE_ENV=test $(DOCKER_COMPOSE_E2E) exec e2e yarn test
endif

test-e2e-stop: ## stop e2e tests services
	NODE_ENV=test $(DOCKER_COMPOSE_E2E) down
	docker volume rm laravel_tutorial_dbdata