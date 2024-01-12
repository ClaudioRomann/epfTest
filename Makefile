build:
	docker build .
start:
	# Starts all containers
	docker-compose up -d

stop:
	# Stops all containers
	docker-compose stop

restart:
	# Restarts all containers
	docker-compose restart

remove:
	# Removes the containers and default network
	docker-compose down

destroy:
	# Destroys all containers, networks, volumes and images
	docker-compose down -v --rmi local

build-image:
	# Builds or rebuilds images
	docker-compose build

bash:
	# Opens a bash terminal into the gbf-php container
	docker exec -it epf_test-php-1 /bin/bash

logs:
	# Displays logs of all containers
	docker-compose logs --tail=100 -f $(c)

status:
	# Show the status of running containers
	docker-compose ps
