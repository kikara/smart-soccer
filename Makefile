DCE = docker-compose exec -T php
ARTISAN = $(DCE) php artisan
COMMAND = $(filter-out $@,$(MAKECMDGOALS))

#>>>docker
start:
	@docker-compose start

stop:
	@docker-compose stop

rebuild:
	@docker-compose up --build -d --no-deps

build:
	@docker-compose up --build
#<<<docker

#>>>docker-container
art:
	@$(ARTISAN) $(COMMAND)

bash:
	@$(DCE) bash
#<<<docker-container
