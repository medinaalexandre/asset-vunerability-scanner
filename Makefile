build:
	docker compose -f .docker/docker-compose.yml up --build -d

bash:
	docker compose -f .docker/docker-compose.yml exec api bash

restart:
	docker compose -f .docker/docker-compose.yml restart

up:
	docker compose -f .docker/docker-compose.yml up -d

test:
	docker exec -i docker-api-1 php artisan test
