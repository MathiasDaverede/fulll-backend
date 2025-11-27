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

## Step 3

Tools for code quality :

- PHP CS Fixer
  - Coding Style/Formatting : Auto-fixes code to comply with standards (e.g., PSR-12, Symfony), ensuring code base consistency.
- PHPStan
  - Static Analysis : Catches deep bugs and type-related errors without execution, significantly increasing code robustness and predictability.
- PHPUnit
  - Unit/Functional Testing : Verifies that individual components work in isolation, preventing technical regressions.
- Behat
  - Behavioral Testing (BDD) : Validates that the entire application meets the defined business requirements from the user's perspective.

CI/CD process :

The goal of the CI/CD pipeline is to ensure that every committed change is automatically tested and safely deployed.

- Continuous Integration (CI)
  - Setup & Dependencies :
    - Build Docker service images and start the containers (web, database).
    - Install dependencies (composer install).
    - Prepare database.
  - Static Checks :
    - Run PHPStan.
    - Run PHP CS Fixer(--dry-run).
    - Run security check (symfony check:security).
    - Run requirments check (symfony check:requirements).
  - Testing & Validation :
    - Prepare test database (--env=test).
    - Execute PHPUnit.
    - Execute Behat.
  - Artifact Generation :
    - If all checks pass, tag and push the final, production-ready Docker image (the Artifact) to a registry.
- Continuous Deployment (CD)
  - Environment Setup :
    - Pull the validated Docker image from the registry to the Production server.
  - Database Migration :
    - Run Doctrine Migrations on the live Production database (--env=prod).
  - Service Update :
    - Deploy the new container image using a zero-downtime strategy (e.g., Rolling Update).
  - Post-Deployment :
    - Clear the Production cache (bin/console cache:clear --env=prod).
    - Run final health checks to confirm the service is operational.
