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
    if [ ! -z "$GITHUB_TOKEN" ]; then
        php composer.phar config github-oauth.github.com "$GITHUB_TOKEN"
    fi
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

if [[ $CMD == up* ]]; then
    APP_ID="$(docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" ps -q app)"
    APP_HOST="http://$(docker port $APP_ID | awk '{split($0,a," -> "); print a[2]}')"
    echo -e "Waiting for host to be available at $APP_HOST, this may take some time\c"
    SECONDS=0
    until $(curl --output /dev/null --silent --head --fail $APP_HOST); do
        printf '.'
        sleep 5
    done
    duration=$SECONDS
    echo
    echo "Startup took $(($duration / 60)) m $(($duration % 60)) s"
    echo "Server ready at $APP_HOST"
fi
