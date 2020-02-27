#!/bin/bash
#
# Script for pruning old containers.
#
# Suggested crontab (run nightly):
#
# 0 3 * * * root /data/ub-baser/scripts/docker-prune.sh

docker container prune -f
docker image prune -a -f
