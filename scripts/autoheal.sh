#!/bin/bash
#
# Script for restarting the container if it has crashed
#
# Suggested crontab (run every minute):
#
# * * * * * root /data/ub-baser/scripts/autoheal.sh

if [ -z "$(docker ps -q -f name=ub-baser)" ]; then
    echo "Restarting ub-baser after crash"
    docker service update --force ub-baser_app
fi

