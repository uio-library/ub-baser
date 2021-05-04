#!/bin/bash

set -e

COMMIT="$(git rev-parse HEAD | cut -c1-7)"

echo "Preparing build for $COMMIT"

if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php --
fi

rm -f composer.lock
php composer.phar install --no-interaction --no-dev --no-autoloader
php composer.phar dump-autoload --optimize

npm ci
npm run production

chmod -R a+rX .

docker build . --file docker/Dockerfile --tag "ub-baser:$COMMIT" --tag "ub-baser:latest"

echo "--------"
echo "DONE"
