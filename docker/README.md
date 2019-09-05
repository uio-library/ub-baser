
Requirements:

* PHP 7.1+
* NodeJS
* Docker and docker-compose

(Postgres is not needed, since we run that from Docker in development and from an external server in production)

# Development setup

## Starting the development server

The [`dev.sh`](https://github.com/scriptotek/ub-baser/blob/master/dev.sh) script can be used to quickly get a Docker-based development server up and running at port 80.
It checks that PHP and NodeJS is present, downloads dependencies using Composer and NPM,
and calls docker-compose with the configuration in
[`docker/compose.dev.yml`](https://github.com/scriptotek/ub-baser/blob/master/docker/compose.dev.yml)
and any arguments given to the script.

To bring everything up, simply run:

	./dev.sh up

And simply press Ctrl-C to stop it.

Any other command supported by docker-compose also works with the `dev.sh` script. E.g. to check status, run:

	./dev.sh ps

Or to start a shell in the app container:

	./dev.sh exec app bash

## Migrating the database and importing data

The first time the server is started, it comes with an empty database.
To migrate it, run:

	./dev.sh run --rm app php artisan migrate --seed

By including `--seed`, a few static pages and an administrator user (user: `admin@example.org`, password: `secret`)
will also be created. Make sure to change this later on.

To also import the data from the `initial-import` folder, run:

    $ ./dev.sh run --rm -v "$(pwd)"/initial-import:/initial-import app php artisan import /initial-import


## Making changes to the Docker image and Apache/PHP config

If you need to rebuild the Docker image, e.g. after having made changes to the Dockerfile, run:

	./dev.sh up --build

You don't need to rebuild the image after changes to the application itself, since the
current directory is mounted into the Docker container when using the development configuration
(see docker/compose.dev.yml).

When testing changes to the Apache config, it can be useful to also mount the config file/folder,
to avoid having to rebuild the image for every change. Here's an example where we mount
the `sites-available` folder:

	$ ./dev.sh run --rm -v "$(pwd)"/docker/sites-available:/etc/apache2/sites-available/ app

## Running tests

Make sure the development server is running:

	APP_ENV=testing ./dev.sh up -d

To run tests:

	APP_ENV=testing npm run test

If you use Docker Machine:

	BASE_URL="http://$(docker-machine ip):8080" npm run test


## Making changes to stylesheets or JavaScripts

Run `npm run watch` to build these resources as you make changes to the source files in the `resources` folder.

# Production setup

## 1. [Install Docker for Centos](https://docs.docker.com/install/linux/docker-ce/centos/)

- [ ] Install Docker:

	```sh
	sudo yum install -y yum-utils device-mapper-persistent-data lvm2
	sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
	sudo yum install docker-ce docker-ce-cli containerd.io
	sudo systemctl start docker
	```

- [ ] Make Docker start at boot:

	```sh
	sudo systemctl enable docker
	```

- [x] Storage engine: We stick with the default, overlay2, for now.

- [ ] Logging engine: Rather than using file-based logging, we want to use the [journald logging driver](https://docs.docker.com/config/containers/logging/journald/). Edit `/etc/docker/daemon.json` (create it if it doesn't exist)
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

- [ ] Time to restart Docker

	```sh
	sudo systemctl restart docker
	```

- [ ] And init Swarm

	```sh
	sudo docker swarm init
	```


## 3. Create a deploy user

- [ ] Create a deploy user:

	```sh
	sudo useradd --system --shell /bin/bash --create-home deploy --comment "Deploy user"
	```

- [ ] Create an SSH key for it:

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

- [ ] Add the SSH key to the GitHub repo as a [deploy key](https://developer.github.com/v3/guides/managing-deploy-keys/). (And remove any keys no longer in use)

- [ ] Add the deploy user to the Docker group:

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

- [ ] As the deploy user, clone the repo:

	```sh
	sudo mkdir /data
	sudo chown -R deploy:deploy /data

	sudo -u deploy bash
	git clone git@github.com:scriptotek/ub-baser.git
	cd ub-baser
	```


- [ ] Build the Docker image:

	```
	./build.sh
	```

- [ ] Define a handy alias, for instance in .bashrc:

	```
	alias RUN="docker run --rm -it --env-file .env -v ub-baser_storage:/app/storage -v "$(pwd)"/initial-import:/initial-import ub-baser:latest"
	```

- [ ] Run database migrations and add seed data:

	```
	RUN php artisan migrate --seed
	```

- [ ] Data import:

    ```
    RUN php artisan import /initial-import
    ```

- [ ] Deploy:

	```
	$ docker stack deploy --compose-file docker/compose/production.yml ub-baser
	Creating network ub-baser_default
	Creating service ub-baser_app
	```

	Ignorer advarselen "image ub-baser:latest could not be accessed on a registry". Vent et par sekunder og sjekk:

	```
	$ docker stack ps ub-baser
	```


Oppdatering:

```
docker service update --force ub-baser_app
```

Feilsøking? Inspisere containeren:

```sh
RUN bash
```
