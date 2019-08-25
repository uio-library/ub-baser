#!/bin/bash

APP_ENV=${APP_ENV:-development}
PROJECT_NAME="ub-baser-${APP_ENV}"
APP_VERSION="$(git rev-parse HEAD)"

if [ ! -f "docker/compose/${APP_ENV}.yml" ]; then
	echo "------------------------------------"
	echo "Invalid environment: '${APP_ENV}'"
	echo "------------------------------------"
	exit 1
fi

echo "------------------------------------"
echo "Using the '${APP_ENV}' environment"
echo "------------------------------------"

# --------------------------------------------------------------------------------
# Check requirements

if ! command -v npm >/dev/null ; then
	echo "NPM not found"
	exit 1
fi

if ! command -v php >/dev/null ; then
	echo "PHP not found"
	exit 1
fi

# --------------------------------------------------------------------------------
# Install dependencies

if [ ! -d "vendor" ]; then
	curl -sS https://getcomposer.org/installer | php --
	php composer.phar install --no-interaction --no-dev --prefer-dist
fi

if [ ! -d "node_modules" ]; then
	npm install
	if [ "${APP_ENV}" == "development" ]; then
		npm run development
	else
		npm run production
	fi
fi


# --------------------------------------------------------------------------------
# Define some extra commands

case $1 in
  laravel-logs)
    CMD="run --rm app tail -f /app/storage/logs/laravel-$(date +%Y-%m-%d).log"
    ;;
  *)
    CMD="$@"
    ;;
esac

# --------------------------------------------------------------------------------
# Start!

docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" $CMD

