export SHELL := /bin/bash
export LOCATION := $(shell pwd)
export HOST_UID := $(shell id -u)
export HOST_GID := $(shell id -g)
export DOCKER_COMPOSE := docker-compose -f ${LOCATION}/docker-compose.yml

default: up ;

# Run the environment
up:
	@${DOCKER_COMPOSE} up -d --remove-orphans

# Upgrade and run the environment
upgrade:
	@${DOCKER_COMPOSE} pull
	@${DOCKER_COMPOSE} up -d --remove-orphans

# Destroy environment but without removing persistent data
destroy:
	@${DOCKER_COMPOSE} down

# Restart the environment
restart:
	@${DOCKER_COMPOSE} restart

# Recreate environment (cleaning files)
recreate:
	@${DOCKER_COMPOSE} up -d --force-recreate

# Composer install
composer:
	@${DOCKER_COMPOSE} exec -T --user user php php composer.phar install

start-local:
	symfony server:start

test:
	php bin/phpunit

test-behat:
	vendor/bin/behat -p backend