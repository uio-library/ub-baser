#!/bin/bash
#
# Script for running the  Artisan scheduler.
#
# Suggested crontab (run every minute):
#
# * * * * * root /data/ub-baser/scripts/artisan-scheduler.sh

sudo -u deploy docker exec --user www-data $(docker ps -q -f name=ub-baser) php artisan schedule:run  > /dev/null

