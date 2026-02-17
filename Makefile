SHELL := /bin/bash

up:
	docker compose up -d

down:
	docker compose down

logs:
	docker compose logs -f

composer:
	docker compose exec app composer install

key:
	docker compose exec app php artisan key:generate

install:
	docker compose exec app php artisan jwt:secret --force

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

test:
	docker compose exec app ./vendor/bin/phpunit

