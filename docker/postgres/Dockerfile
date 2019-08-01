FROM postgres:12

RUN localedef -i nb_NO -c -f UTF-8 -A /usr/share/locale/locale.alias nb_NO.UTF-8

COPY init.sh /docker-entrypoint-initdb.d/
