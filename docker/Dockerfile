# Usage:
#
# docker build --build-arg GITHUB_TOKEN=$GITHUB_TOKEN --tag ub-baser:latest .
#

# ---------------------------------------------------------------------------
FROM registry.redhat.io/ubi8/php-73

LABEL maintainer="d.m.heggo@ub.uio.no"

ARG GITHUB_TOKEN

WORKDIR /app/


# RUN apt-get update \
#     && apt-get install -y libvips-dev \
#     && pecl install vips \
#     && docker-php-ext-enable vips


#RUN a2enmod rewrite ssl headers
#RUN a2dissite 000-default

USER root

RUN mkdir -p \
    ./storage/app \
    ./storage/framework/cache \
    ./storage/framework/sessions \
    ./storage/framework/views \
    ./storage/import \
    ./storage/logs \
  && chown -R default ./storage

COPY docker/include $APACHE_CONFDIR/include
COPY docker/sites-available $APACHE_CONFDIR/sites-available
COPY . .

# Validate Apache config
# RUN /docker-entrypoint.sh -t -D DUMP_VHOSTS

# 451MB

ENTRYPOINT ["/app/docker/entrypoint.sh"]
CMD ["/app/docker/start.sh"]
