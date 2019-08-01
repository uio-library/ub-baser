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

if [ "$APP_ENV" == "production" ]; then
	echo "Copying opcache settings into $PHP_INI_DIR/conf.d/"
	cp docker/opcache.ini $PHP_INI_DIR/conf.d/
fi

exec apache2-foreground "$@"
