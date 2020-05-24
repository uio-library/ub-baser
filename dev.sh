#!/bin/bash
set -euo pipefail

APP_ENV=${APP_ENV:-development}
PROJECT_NAME="ub-baser-${APP_ENV}"
APP_VERSION="$(git rev-parse HEAD)"

if [ ! -f "docker/compose/${APP_ENV}.yml" ]; then
	echo "============================================================================================================"
	echo "[dev.sh] ERROR: Invalid environment: '${APP_ENV}'"
	echo "============================================================================================================"
	exit 1
fi

echo "============================================================================================================"
echo "[dev.sh] Starting using '${APP_ENV}' env @ ${APP_VERSION}"
echo "============================================================================================================"

# --------------------------------------------------------------------------------
# Check requirements

if ! command -v npm >/dev/null ; then
	echo "[dev.sh] ERROR: NPM not found"
	exit 1
fi

if ! command -v php >/dev/null ; then
	echo "[dev.sh] ERROR: PHP not found"
	exit 1
fi

# --------------------------------------------------------------------------------
# Install dependencies

if [ ! -d "vendor" ]; then
  echo "============================================================================================================"
  echo "[dev.sh] composer install"
  echo "============================================================================================================"

	curl -sS https://getcomposer.org/installer | php --
  if [ ! -z "$GITHUB_TOKEN" ]; then
    php composer.phar config github-oauth.github.com "$GITHUB_TOKEN"
  fi
  php composer.phar install --no-interaction --prefer-dist
fi

if [ ! -d "node_modules" ]; then
  echo "============================================================================================================"
  echo "[dev.sh] npm install"
  echo "============================================================================================================"
	npm install

  echo "============================================================================================================"
  echo "[dev.sh] npm build"
  echo "============================================================================================================"
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

docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" ps

docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" logs

if [[ $CMD == "up -d"* ]]; then

    echo "============================================================================================================"
    echo "[dev.sh] Waiting for app to be ready"
    echo "============================================================================================================"

    APP_ID="$(docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" ps -q app)"
    APP_PORT="$(docker port $APP_ID | awk '{split($0,a,":"); print a[2]}')"
    APP_HOST="http://localhost:${APP_PORT}"
    echo "Waiting for host to be available at $APP_HOST, this may take some time"
    SECONDS=0
    until $(curl --output /dev/null --silent --head --fail $APP_HOST); do
        echo "[dev.sh]] Still not ready"
        docker-compose -f "docker/compose/${APP_ENV}.yml" -p "${PROJECT_NAME}" exec -T app sh -c 'tail storage/logs/*.log || echo "No log files yet"'
        sleep 5
    done
    duration=$SECONDS
    echo
    echo "Startup took $(($duration / 60)) m $(($duration % 60)) s"
    echo "Server ready at $APP_HOST"
fi
