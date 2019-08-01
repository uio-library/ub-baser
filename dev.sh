#!/bin/bash

if ! command -v npm >/dev/null ; then
	echo "NPM not found"
	exit 1
fi

if ! command -v php >/dev/null ; then
	echo "PHP not found"
	exit 1
fi

if [ ! -d "vendor" ]; then
	curl -sS https://getcomposer.org/installer | php --
	php composer.phar install --no-interaction --no-dev --no-autoloader
fi

if [ ! -d "node_modules" ]; then
	npm install
	npm run dev
fi

docker-compose -f docker/compose.dev.yml "$@"
