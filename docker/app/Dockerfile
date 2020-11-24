FROM php:7.4-fpm-alpine


RUN addgroup -g 1000 www \
    && adduser -D -u 1000 -G www www

RUN apk add --no-cache zlib-dev freetype-dev libzip-dev libjpeg-turbo-dev libpng-dev libressl-dev

RUN docker-php-ext-install opcache zip

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql
# RUN docker-php-ext-install iconv gd \
# && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install xdebug\
    && docker-php-ext-enable xdebug\
    && apk del --no-network .phpize-deps

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# COPY xdebug.ini $PHP_INI_DIR/conf.d
# ARG XDEBUG_REMOTE_HOST="localhost"
# RUN sed -i "s|xdebug.remote_host = localhost|xdebug.remote_host = $XDEBUG_REMOTE_HOST|g" $PHP_INI_DIR/conf.d/xdebug.ini

RUN echo "xdebug.remote_autostart=1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_enable=1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_host=host.docker.internal" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_port=9000" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_handler=dbgp" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.remote_connect_back=1" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER www
