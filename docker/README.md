
# Development setup


## Useful tips


Dev:

Start a dev server:

	./dev.sh up --build

Run migrations:

	./dev.sh run --rm app php artisan migrate --seed

When testing changes in the Apache config, it can be useful to mount the config file
to avoid having to rebuild the image for every change:

	$ ./dev.sh run --rm -p 80:80 -p 443:443 -v "$(pwd)"/docker/sites-available:/etc/apache2/sites-available/ app bash
	$ /docker-entrypoint.sh

Initial data import:

    $ ./dev.sh run --rm -v "$(pwd)"/initial-import:/initial-import app php artisan import /initial-import

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
	sudo useradd --system --shell /sbin/nologin --create-home deploy --comment "Deploy user"
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


## 4. Deploy the app

- [ ] As the deploy user, clone the repo:

	```sh
	sudo mkdir /data
	sudo chown -R deploy:deploy /data

	sudo -u deploy bash
	git clone git@github.com:scriptotek/ub-baser.git
	cd ub-baser
	```


docker magic time!



	```sh
	$ ./build.sh
	$ docker stack deploy --compose-file docker/compose.prod.yml ub-baser
	[ vent et par sekunder]
	$ docker stack ps ub-baser
	```

