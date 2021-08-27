# ---------------------------------------------------------------------------
# Usage:
#
# docker build --build-arg GITHUB_TOKEN=$GITHUB_TOKEN --tag ub-baser:latest .
#
# ---------------------------------------------------------------------------

FROM node:12 AS frontend
WORKDIR /app
COPY artisan package.json package-lock.json webpack.mix.js ./
#RUN npm install -g --upgrade npm
RUN npm install
COPY resources/ ./resources/
#COPY resources/js ./resources/js
#COPY resources/sass ./resources/sass
RUN npm ci && npm run production

# ------------------------------------------------------------------------------------
# Notes:
# - We need to include Node and NPM to run tests.
# - Tests need to be able to run composer migrate, so it cannot be a separate image.
# It would be possible to create a smaller production image, but I'm not sure if the
# maintenance overhead is worth it.
# ------------------------------------------------------------------------------------
FROM php:7.4-apache

LABEL maintainer="d.m.heggo@ub.uio.no"

ARG GITHUB_TOKEN

WORKDIR /app

ENV NVM_DIR /usr/local/nvm

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
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql gd zip \
    && mkdir -p $NVM_DIR \
    && curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install lts/erbium \
    && nvm alias default lts/erbium \
    && nvm use default

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json .
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-interaction --no-autoloader

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
COPY --from=frontend /app/package-lock.json ./
COPY --from=frontend /app/node_modules/ ./node_modules/
COPY --from=frontend /app/public/js/ ./public/js/
COPY --from=frontend /app/public/css/ ./public/css/
COPY --from=frontend /app/public/fonts/ ./public/fonts/
COPY --from=frontend /app/public/images/ ./public/images/
COPY --from=frontend /app/public/vendors~js/ ./public/vendors~js/
COPY --from=frontend /app/public/mix-manifest.json ./public/mix-manifest.json
COPY tests/ ./tests/

RUN composer dump-autoload --optimize

#RUN php artisan config:cache
#RUN php artisan route:cache

# Validate Apache config
# RUN /docker-entrypoint.sh -t -D DUMP_VHOSTS

# 451MB

ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["/app/docker/start.sh"]
