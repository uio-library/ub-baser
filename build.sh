#!/bin/bash

set -e

COMMIT="$(git rev-parse HEAD | cut -c1-7)"

echo "Preparing build for $COMMIT"

if [ ! -f "composer.phar" ]; then
    curl -sS https://getcomposer.org/installer | php --
fi

php composer.phar install --no-interaction --no-dev --no-autoloader

php composer.phar dump-autoload --optimize
php artisan route:cache
php artisan view:cache

npm install
npm run production

chmod -R a+rX .

docker build . --file docker/Dockerfile --tag "ub-baser:$COMMIT" --tag "ub-baser:latest"

# Back to devenv
php artisan route:clear
php artisan view:clear


echo "--------"
echo "DONE"