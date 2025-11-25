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

## Create a .env.local file in the root directory of the project

### User data

USER_NAME=your_user_name (`whoami`)  
USER_ID=your_uid (`id -u`)  
GROUP_ID=your_gid (`id -g`)

## Start the project

Place yourself in the project folder :  
`cd path/to/fulll-backend/`

Construct images and start containers in detach mode :  
`docker compose --env-file .env.local up -d`

Access to Symfony container :  
`docker exec -it fulll-backend-web-1 bash`

Install Symfony dependencies :  
`composer install`

Run Behat command:  
`vendor/bin/behat`
