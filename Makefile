DC = docker compose
PHP = $(DC) exec app

.PHONY: up down build restart logs shell setup setup-prod migrate test build-assets fresh

up: ## Start the whole stack in the background
	$(DC) up -d

down: ## Stop the stack
	$(DC) down

build: ## (Re)build the PHP image
	$(DC) build

restart: down up

logs: ## Follow the logs of all services
	$(DC) logs -f

shell: ## Open a shell inside the PHP container
	$(PHP) bash

setup: ## Install the app with demo data (admin: howdy@hi5ve.digital / vuefilemanager)
	$(PHP) php artisan setup:dev

setup-prod: ## Install the app without demo data
	$(PHP) php artisan setup:prod

migrate:
	$(PHP) php artisan migrate

test: ## Run the PHP test suite
	$(PHP) php artisan test

build-assets: ## Build production front-end assets into public/build
	$(DC) run --rm vite npm run build

fresh: ## Destroy all containers AND volumes (database, redis) — careful!
	$(DC) down -v
