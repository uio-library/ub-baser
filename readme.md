[![Build Status](https://github.com/uio-library/ub-baser/actions/workflows/ci.yml/badge.svg)](https://github.com/uio-library/ub-baser/actions/workflows/ci.yml)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=scriptotek_ub-baser&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=scriptotek_ub-baser)

# UB-baser

Felles webgrensesnitt for mindre Postgres-baser driftet av Universitetsbiblioteket i Oslo som Letras
(norske oversettelser av spansk og spanskamerikansk litteratur), Norsk Litteraturkrtikk
(Beyer-basen)og Dommers populærnavn.

## Local development

Requirements:

* PHP 7.1 and [Composer](https://getcomposer.org/)
* NodeJS 18
* Docker and docker-compose

The app can also be run locally without Docker, but then you need to install Apache (or another web
server) and Postgres.

### Getting started

1. `npm install` to install frontend dependencies into `./node_modules`
2. `npm run build` to build frontend components (CSS, JS)
3. `composer install` to install PHP dependencies into `./vendor`
4. `docker-compose up` to start the app.

Docker Compose starts two containers: `db` (the Postgres database) and `app` (Apache + PHP).

When the `app` container starts, it will first run the `entrypoint.sh` script
which takes care of [migrating](https://laravel.com/docs/master/migrations) the database,
creating all neccessary tables, collations etc.

Once ready, the app will be available at <http://localhost:8080>

To stop the server, press Ctrl-C.

#### Creating the first admin user

The `create:admin` artisan command is provided to create the initial admin user with a default
password, which can then be used to create additional users.

Without Docker: (requires local Postgres)

    php artisan create:admin

With Docker:

    docker-compose run --rm app php artisan create:admin

The source for this command is `app/Console/Commands/CreateAdminCommand.php`

Now you should be able to login at <http://localhost:8080/login>
and add additional rights at <http://localhost:8080/admin/users/1/edit>

#### Importing data

Data can be imported from the `initial-import` folder using the import commands. For instance you
can import data from `initial-import/litteraturkritikk` to the `litteraturkritikk` database like so:

Without Docker: (requires local Postgres install)

    php artisan import:litteraturkritikk initial-import/litteraturkritikk

With Docker:

    docker-compose run --rm -v "$(pwd)"/initial-import:/initial-import app php artisan import:litteraturkritikk /initial-import/litteraturkritikk

The source for this command is `app/Console/Commands/ImportLitteraturkritikkCommand.php`.

### Troubleshooting local development

If the app container fails to start, try rebuilding the image:

    docker-compose build

To clean up and start from scratch:

    docker-compose down
    docker system prune
    docker volume prune   # Note: Deletes the local database

### Resetting the database

If you need to delete the database and start over again, use the `migrate:fresh` artisan command:

 docker-compose run --rm app php artisan migrate:fresh

## Making changes to the Docker image and Apache/PHP config

If you need to rebuild the Docker image, e.g. after having made changes to the Dockerfile, run:

 docker-compose up --build

You don't need to rebuild the image after changes to the application itself, since the
current directory is mounted into the Docker container when using the development configuration
(see docker/compose.dev.yml).

When testing changes to the Apache config, it can be useful to also mount the config file/folder,
to avoid having to rebuild the image for every change. Here's an example where we mount
the `sites-available` folder:

 docker-compose run --rm -v "$(pwd)"/docker/sites-available:/etc/apache2/sites-available/ app

## Running tests

Tests will run in the `staging` environment by default,
so that tests will run isolated from your development environment.
To start containers for this environment:

 APP_ENV=staging docker-compose up -d

Run the WebdriverIO tests against <http://localhost:8080>:

 npm run test

To run tests against another host, you can specify `TEST_BASE_URL`.
If you use Docker Machine:

 TEST_BASE_URL="http://$(docker-machine ip):8081" npm run test

To run a single test:

    APP_ENV=staging npx wdio tests/wdio.conf.js --spec ./tests/selenium/specs/login.js

## Making changes to stylesheets or JavaScripts

Run `npm run watch` to build these resources as you make changes to the source files in the `resources` folder.

# Production setup

Note: Day-to-day deployments are done using Ansible. See ../ansible/README.md

## 1. [Install Docker for Centos](https://docs.docker.com/install/linux/docker-ce/centos/)

* [ ] Install Docker:

 ```sh
 sudo yum install -y yum-utils device-mapper-persistent-data lvm2
 sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
 sudo yum install docker-ce docker-ce-cli containerd.io
 sudo systemctl start docker
 ```

* [ ] Make Docker start at boot:

 ```sh
 sudo systemctl enable docker
 ```

* [x] Storage engine: We stick with the default, overlay2, for now.

* [ ] Logging engine: Rather than using file-based logging, we want to use the [journald logging driver](https://docs.docker.com/config/containers/logging/journald/). Edit `/etc/docker/daemon.json` (create it if it doesn't exist)
with the following content:

 ```
 {
   "log-driver": "journald",
   "log-opts": {
     "tag": "{{.ImageName}}:{{.ID}}"
   }
 }
 ```

 This enables us to retrieve logs both with the `docker logs` command and with journalctl:

 ```sh
 sudo journalctl CONTAINER_NAME=app
 ```

* [ ] Time to restart Docker

 ```sh
 sudo systemctl restart docker
 ```

* [ ] And init Swarm

 ```sh
 sudo docker swarm init
 ```

## 3. Create a deploy user

* [ ] Create a deploy user:

 ```sh
 sudo useradd --system --shell /bin/bash --create-home deploy --comment "Deploy user"
 ```

* [ ] Create an SSH key for it:

 ```sh
 sudo -u deploy bash
 mkdir -p /home/deploy/.ssh
 touch /home/deploy/.ssh/authorized_keys
 ssh-keygen -t rsa -b 4096 -f /home/deploy/.ssh/id_rsa.github -C "ub-baser deploy key for github"

 cat <<EOF > /home/deploy/.ssh/config
 Host github.com
   IdentityFile ~/.ssh/id_rsa.github
 EOF

 restorecon -Rv /home/deploy
 ```

* [ ] Add the SSH key to the GitHub repo as a [deploy key](https://developer.github.com/v3/guides/managing-deploy-keys/). (And remove any keys no longer in use)

* [ ] Add the deploy user to the Docker group:

 ```sh
 sudo usermod -aG docker deploy
 ```

## 4. Add configuration to `.env`

 Copy the template file:

 ```sh
 cp .env.prod.example .env
 ```

 and add database settings and a ssl certificate.

## 5. Deploy the app

* [ ] As the deploy user, clone the repo:

 ```sh
 sudo mkdir /data
 sudo chown -R deploy:deploy /data

 sudo -u deploy bash
 git clone git@github.com:scriptotek/ub-baser.git
 cd ub-baser
 ```

* [ ] Build the Docker image:

 ```
 ./build.sh
 ```

* [ ] Define a handy alias, for instance in .bashrc:

 ```
 alias RUN="docker run --rm -it --env-file .env -v ub-baser_storage:/app/storage -v "$(pwd)"/initial-import:/initial-import ub-baser:latest"
 ```

* [ ] Run database migrations and add seed data:

 ```
 RUN php artisan migrate --seed
 ```

* [ ] Data import:

    ```
    RUN php artisan import /initial-import
    ```

* [ ] Deploy:

 ```
 $ docker stack deploy --compose-file docker/compose/production.yml ub-baser
 Creating network ub-baser_default
 Creating service ub-baser_app
 ```

 Ignorer advarselen "image ub-baser:latest could not be accessed on a registry". Vent et par sekunder og sjekk:

 ```
 docker stack ps ub-baser
 ```


Oppdatering:

```
docker service update --force ub-baser_app
```

Feilsøking? Inspisere containeren:

```sh
RUN bash
```
