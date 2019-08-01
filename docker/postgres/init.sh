#!/bin/bash
set -e

psql -c 'CREATE COLLATION "nb_NO.utf8" (LOCALE="nb_NO.UTF-8")'
