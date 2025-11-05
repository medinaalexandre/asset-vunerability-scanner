build:
	docker compose up --build -d \
	&& docker compose exec api composer install \
	&& docker compose exec api php artisan migrate:fresh

bash:
	docker compose exec api bash

restart:
	docker compose restart

up:
	docker compose up -d

test:
	docker compose exec -i api php artisan test
