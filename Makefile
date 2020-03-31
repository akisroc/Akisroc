.PHONY         =  help prod install test build start kill reset clean confirm watch assets database migration
.DEFAULT_GOAL  =  help

DOCKER_COMPOSE = docker-compose

EXEC_PHP       = $(DOCKER_COMPOSE) exec -T php /entrypoint
EXEC_JS        = $(DOCKER_COMPOSE) exec -T node /entrypoint

CONSOLE        = $(EXEC_PHP) bin/console
COMPOSER       = $(EXEC_PHP) composer
NPM            = $(EXEC_JS) npm


## --------------------------------
##     Main commands
## --------------------------------

help: ## Display this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: .env build start vendor node_modules assets database ## Build for development

start: ## Start the project
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop:  ## Stop the project
	$(DOCKER_COMPOSE) stop

clean: confirm kill ## Stop the project and remove generated files
	rm -rf .env vendor node_modules

reset: clean install ## Start a fresh install of the project


## --------------------------------
##     Environment
## --------------------------------

confirm:
	@echo -n "Are you sure? [y/N] " && read ans && [ $${ans:-N} = y ]

build:
	@$(DOCKER_COMPOSE) pull --quiet --ignore-pull-failures
	$(DOCKER_COMPOSE) build --pull

kill: confirm
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

test: vendor phpunit.xml ## Run all test suites
	$(EXEC_PHP) bin/phpunit tests/

migration: database ## Generate a new Doctrine migration
	$(CONSOLE) doctrine:migrations:diff

database: vendor .env ## Create database from Doctrine schema
	echo "Waiting 30 seconds for database to initiate...\n"; sleep 30
	-$(CONSOLE) doctrine:database:drop --if-exists --force
	-$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:schema:create
	#Todo: $(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration
	echo -en 'Root\nroot@root.fr\nRoot' > src/DataFixtures/Prod/.apwd
	$(CONSOLE) doctrine:fixtures:load --no-interaction
	rm src/DataFixtures/Prod/.apwd


## --------------------------------
##     Dependencies and assets
## --------------------------------

watch: node_modules ## Watch assets
	$(NPM) run watch

assets: node_modules ## Build assets
	$(NPM) run dev

node_modules: package-lock.json ## Install NPM dependencies
	$(NPM) install

vendor: composer.lock ## Install Composer dependencies
	$(COMPOSER) install

composer.lock: composer.json ## Update Composer dependencies
	$(COMPOSER) update

package-lock.json: package.json ## Update NPM dependencies
	$(NPM) update

.env: .env.dist ## Create .env file from .env.dist
	@if [ -f .env ]; \
	then\
		echo 'The .env.dist file has changed. Please check your .env file (this message will not be displayed again)';\
		touch .env;\
		exit 1;\
	else\
		echo cp .env.dist .env;\
		cp .env.dist .env;\
	fi

phpunit.xml: phpunit.xml.dist ## Create phpunit.xml file from phpunit.xml.dist
	cp phpunit.xml.dist phpunit.xml


## --------------------------------
##     Production build
## --------------------------------

prod: ## Build for production
	composer install --no-dev -o -a
	php bin/console cache:clear --env=prod --no-debug
	php bin/console doctrine:migrations:migrate
	npm install
	npm run build
