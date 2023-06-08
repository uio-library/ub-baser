#!/bin/bash
#
# Script for updating the Docker image
#
# Suggested crontab (run every minute):
#
# 0 2 * * * root /data/ub-baser/scripts/update-docker-image.sh


docker pull ghcr.io/uio-library/ub-baser:main
docker service update --force --image ghcr.io/uio-library/ub-baser:main ub-baser_app

