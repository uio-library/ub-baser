#!/usr/bin/env bash
set -e

function log {
    echo "[start.sh] $(date -u +'%Y-%m-%d %H:%M:%S.%3N UTC'): $@"
}

log "Starting using env: ${APP_ENV}, version: ${APP_VERSION}"
if [ -f .env ]; then
    log "Local .env file exists"
    grep "CACHE_DRIVER" .env || echo "CACHE_DRIVER not set"
fi

# ----------------------------------------------------------------------------
# Apache setup

if [ "$APP_ENV" == "production" ]; then

	if [ -z "$SITE_CERTIFICATE" ] ; then
		log "Cannot start without a SITE_CERTIFICATE"
		exit 1;
	fi

	if [ -z "$CA_CERTIFICATE" ] ; then
		log "Cannot start without a CA_CERTIFICATE"
		exit 1;
	fi

	if [ -z "$SITE_CERTIFICATE_KEY" ] ; then
		log "Cannot start without a SITE_CERTIFICATE_KEY"
		exit 1;
	fi

	log "Storing certificates in $APACHE_CONFDIR"

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
    log "Creating config cache"
	php artisan config:cache  # stored in bootstrap/cache/config.php
    log "Creating route cache"
	php artisan route:cache  # stored in bootstrap/cache/routes.php
    log "Creating view cache"
	php artisan view:cache  # stored in storage/framework/views
else
    log "Clearing caches"
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
fi

# ----------------------------------------------------------------------------
# Use opcache if in production

if [ "$APP_ENV" == "production" ]; then
	log "Copying opcache settings into $PHP_INI_DIR/conf.d/"
	cp docker/opcache.ini $PHP_INI_DIR/conf.d/
fi

# ----------------------------------------------------------------------------
# Run migrations, if any

log "Running migrations"
php artisan migrate --force

log "Sending deploy notification"
php artisan ub-baser:deployed

# ----------------------------------------------------------------------------
# Update route cache after migration step to make sure all dynamic routes are included.

if [ "$APP_ENV" == "production" ]; then
    log "Updating route cache"
    php artisan route:cache  # stored in bootstrap/cache/config.php
fi

# ----------------------------------------------------------------------------
# Fix permissions
# This should be done *after* any artisan command, to avoid being left with
# files owned by root.
log "Fixing permissions"
chown -R www-data:www-data storage

# ----------------------------------------------------------------------------
# Apache time!

log "Startup script took $SECONDS seconds"
exec apache2-foreground "$@"
