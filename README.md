# Fulll - backend

![Docker](https://img.shields.io/badge/Docker-28.1-blue)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4)

Fulll's backend technical test

## Prerequisites

- Git
- Docker and Docker Compose

## How to use

Clone the project :  
`git clone git@github.com:MathiasDaverede/fulll-backend.git`

## Modify the .env file

It's optional, but for example if you are already using the database port

### User data

USER_NAME=your_user_name (`whoami`)  
USER_ID=your_uid (`id -u`)  
GROUP_ID=your_gid (`id -g`)

### Database data

POSTGRES_VERSION=16  
POSTGRES_DB=a_database_name  
POSTGRES_PORT=a_not_already_used_port  
POSTGRES_USER=a_database_user_name  
POSTGRES_PASSWORD=a_database_password

## Start the project

Place yourself in the project folder :  
`cd path/to/fulll-backend/`

Construct images and start containers in detach mode :  
`docker compose up -d`

Access to Symfony container :  
`docker exec -it fulll-backend-web-1 bash`

Install Symfony dependencies :  
`composer install`

Make databases migrations :  

```bash
bin/console doctrine:migrations:migrate --no-interaction
bin/console --env=test doctrine:database:create
bin/console --env=test doctrine:migrations:migrate --no-interaction
```

Run Behat command:  
`vendor/bin/behat`

Run technical test commands :

```bash
bin/console fleet:create <userId>
bin/console fleet:register-vehicle <fleetId> <vehiclePlateNumber>
bin/console fleet:park-vehicle <fleetId> <vehiclePlateNumber> lat lng [alt]
```
