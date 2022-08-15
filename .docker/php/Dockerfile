FROM php:8.0-fpm

RUN apt-get update \
  && apt-get install -y \
             apt-utils \
             icu-devtools \
             libicu-dev \
             libfreetype6-dev \
             procps \
    && pecl install xdebug \
    && docker-php-ext-install \
        mysqli pdo_mysql \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete