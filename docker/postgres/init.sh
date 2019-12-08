#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL1
	CREATE COLLATION "nb_NO.utf8" (LOCALE="nb_NO.UTF-8");
EOSQL1
