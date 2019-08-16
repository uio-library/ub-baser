#!/bin/bash

set -e

# ----------------------------------------------------------------------------
# Certificate handling

if [ -z "$SITE_CERTIFICATE" ] ; then
	echo "Cannot start without a SITE_CERTIFICATE"
	exit 1;
fi

if [ -z "$SITE_CERTIFICATE_KEY" ] ; then
	echo "Cannot start without a SITE_CERTIFICATE_KEY"
	exit 1;
fi

echo "Storing certificates in $APACHE_CONFDIR"

echo -e $SITE_CERTIFICATE > $APACHE_CONFDIR/site.crt
echo -e $SITE_CERTIFICATE_KEY > $APACHE_CONFDIR/site.key

if [ -z "$CA_CERTIFICATE" ] ; then
	echo "Warning: No CA_CERTIFICATE specified"
	echo -e $SITE_CERTIFICATE > $APACHE_CONFDIR/ca.crt
else
	echo -e $CA_CERTIFICATE > $APACHE_CONFDIR/ca.crt
fi

# ----------------------------------------------------------------------------
# Fix permissions
chmod -R a+rX .

# ----------------------------------------------------------------------------
# Optimize Laravel
# (These commands depend on the environment, so we cannot run them in Dockerfile)

if [ "$APP_ENV" == "production" ]; then
	php artisan config:cache  # stored in bootstrap/cache/config.php
	php artisan route:cache  # stored in bootstrap/cache/routes.php
	php artisan view:cache  # stored in storage/framework/views
else
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
fi

# ----------------------------------------------------------------------------
# Use opcache if in production

if [ "$APP_ENV" == "production" ]; then
	echo "Copying opcache settings into $PHP_INI_DIR/conf.d/"
	cp docker/opcache.ini $PHP_INI_DIR/conf.d/
fi

# ----------------------------------------------------------------------------
# Wait for Postgres

echo Waiting for ${DB_HOST}:${DB_PORT}
docker/wait-for-it.sh ${DB_HOST}:${DB_PORT} -t 30

# ----------------------------------------------------------------------------
# Run migrations, if any

php artisan migrate --force

# ----------------------------------------------------------------------------
# Apache time!

echo "Startup took $SECONDS seconds"
exec apache2-foreground "$@"
