init: docker-down-clear docker-pull docker-build docker-up
up: sail-up
down: sail-down
restart: down up
restart-sail: sail-down sail-up

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

sail-up:
	./vendor/bin/sail up -d

sail-down:
	./vendor/bin/sail down --remove-orphans

clear-log:
	echo '' > storage/logs/laravel.log
