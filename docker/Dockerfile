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

# RUN apt-get update \
#     && apt-get install -y libvips-dev \
#     && pecl install vips \
#     && docker-php-ext-enable vips

RUN apt-get update && apt-get install -y \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN a2enmod rewrite ssl headers
RUN a2dissite 000-default

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/memory_limit = .*/memory_limit = 512M/" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/post_max_size = .*/post_max_size = 128M/" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/upload_max_filesize = .*/upload_max_filesize = 128M/" "$PHP_INI_DIR/php.ini"

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

ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["/app/docker/start.sh"]
