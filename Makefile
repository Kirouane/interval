all: help

help: ## Show help
	@grep -E '(^[a-zA-Z0-9_\-\.]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

install: image.build start php.install ## Installs everything: dependencies, database, assets, etc.

image.build: ## builds docker images
	docker-compose build

php.install: ## Installs composer dependencies
	docker-compose run --rm -T php composer install --no-interaction


start: ## Starts docker-compose
	docker-compose up -d

stop: ## Stops docker-compose
	docker-compose down --remove-orphans

restart: stop start ## Stops and starts docker-compose

php.shell:
	docker-compose exec php bash

test:
	docker-compose exec php vendor/bin/phpunit -c phpunit.xml

logs.watch:
	docker-compose logs -f

csfixer.fix: #fix PHP CS Fixer issues
	docker-compose exec vendor/bin/php-cs-fixer fix