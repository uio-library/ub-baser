#!/usr/bin/env bash
set -e

. /usr/local/nvm/nvm.sh

docker/wait-for-it.sh ${POSTGRES_HOST}:${POSTGRES_PORT} -t 30
sleep 3

exec "$@"
