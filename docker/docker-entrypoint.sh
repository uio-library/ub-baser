#!/bin/bash

set -e

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

# Optimize Laravel. These commands depend on the environment,
# so we cannot run them in the Dockerfile.
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Sanity check
chmod -R a+rX .

if [ "$APP_ENV" == "production" ]; then
	echo "Copying opcache settings into $PHP_INI_DIR/conf.d/"
	cp docker/opcache.ini $PHP_INI_DIR/conf.d/
fi

# Run new migrations, if any
php artisan migrate

exec apache2-foreground "$@"
