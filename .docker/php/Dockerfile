FROM php:8.0-fpm

ARG NODE_VERSION=18
ARG POSTRGRES_VERSION=14

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
    && docker-php-source delete \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Composer:
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm


RUN rm -r /var/lib/apt/lists/*

COPY composer.* ./

RUN chown -R www-data:www-data /var/www

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/dev dev
RUN mkdir -p /home/dev/.composer && \
    chown -R dev:dev /home/dev


ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/smart-soccer
USER dev

