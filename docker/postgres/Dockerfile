FROM postgres:12

RUN localedef -i nb_NO -c -f UTF-8 -A /usr/share/locale/locale.alias nb_NO.UTF-8

# Warning: scripts in /docker-entrypoint-initdb.d are only run
# if you start the container with a data directory that is empty.
COPY init.sh /docker-entrypoint-initdb.d/
