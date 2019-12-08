#!/bin/bash

set -e

echo "entrypoint.sh starting using env: ${APP_ENV}, version: ${APP_VERSION}"

# ----------------------------------------------------------------------------
# Apache setup

if [ "$APP_ENV" == "production" ]; then

	if [ -z "$SITE_CERTIFICATE" ] ; then
		echo "Cannot start without a SITE_CERTIFICATE"
		exit 1;
	fi

	if [ -z "$CA_CERTIFICATE" ] ; then
		echo "Cannot start without a CA_CERTIFICATE"
		exit 1;
	fi

	if [ -z "$SITE_CERTIFICATE_KEY" ] ; then
		echo "Cannot start without a SITE_CERTIFICATE_KEY"
		exit 1;
	fi

	echo "Storing certificates in $APACHE_CONFDIR"

	echo -e "-----BEGIN CERTIFICATE-----\n${CA_CERTIFICATE}\n-----END CERTIFICATE-----" > $APACHE_CONFDIR/ca.crt
	echo -e "-----BEGIN CERTIFICATE-----\n${SITE_CERTIFICATE}\n-----END CERTIFICATE-----" > $APACHE_CONFDIR/site.crt
	echo -e "-----BEGIN PRIVATE KEY-----\n${SITE_CERTIFICATE_KEY}\n-----END PRIVATE KEY-----" > $APACHE_CONFDIR/site.key

	a2ensite production

else

	a2ensite development

fi

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

echo Waiting for ${POSTGRES_HOST}:${POSTGRES_PORT}
docker/wait-for-it.sh ${POSTGRES_HOST}:${POSTGRES_PORT} -t 30

# ----------------------------------------------------------------------------
# Run migrations, if any

php artisan migrate --force
php artisan ub-baser:deployed

# ----------------------------------------------------------------------------
# Fix permissions
# This should be done *after* any artisan command, to avoid being left with
# files owned by root.
chmod -R a+rX .
chown -R www-data:www-data storage

# ----------------------------------------------------------------------------
# Apache time!

echo "Startup took $SECONDS seconds"
exec apache2-foreground "$@"
