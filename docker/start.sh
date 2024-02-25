#!/usr/bin/env bash
set -e

# Activate Apache site
a2ensite ub-baser-dev

echo "Clearing caches"
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ----------------------------------------------------------------------------
# Run migrations, if any

echo "Running migrations"
php artisan migrate --force

# ----------------------------------------------------------------------------
# Fix permissions
# This should be done *after* any artisan command, to avoid being left with
# files owned by root.
echo "Fixing permissions"
chown -R www-data:www-data storage

# ----------------------------------------------------------------------------
# Apache time!

echo "Startup script took $SECONDS seconds"
exec apache2-foreground "$@"
