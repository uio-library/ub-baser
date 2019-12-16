# Usage:
#
# docker build --build-arg GITHUB_TOKEN=$GITHUB_TOKEN --tag ub-baser:latest .
#

# ---------------------------------------------------------------------------
FROM php:7.4-apache

LABEL maintainer="d.m.heggo@ub.uio.no"

ARG GITHUB_TOKEN

WORKDIR /app/

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite ssl headers
RUN a2dissite 000-default

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/memory_limit = 128M/memory_limit = 348M/" "$PHP_INI_DIR/php.ini"

RUN mkdir -p \
    ./storage/app \
    ./storage/framework/cache \
    ./storage/framework/sessions \
    ./storage/framework/views \
    ./storage/import \
    ./storage/logs \
  && chown -R www-data:www-data ./storage

COPY docker/include $APACHE_CONFDIR/include
COPY docker/sites-available $APACHE_CONFDIR/sites-available
COPY . .

# Validate Apache config
# RUN /docker-entrypoint.sh -t -D DUMP_VHOSTS

# 451MB

CMD ["/app/docker/entrypoint.sh"]
