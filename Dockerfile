FROM php:8.1-apache

LABEL maintainer="ahmedmo@ub.uio.no"

WORKDIR /app

RUN apt-get update \
    && apt-get install -y \
        git \
        libpq-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libzip-dev \
        curl \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql gd zip

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

ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["/app/docker/start.sh"]
