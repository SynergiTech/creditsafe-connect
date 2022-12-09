ARG PHP_VERSION=7.3
FROM php:$PHP_VERSION-cli-alpine

RUN apk add git zip unzip autoconf make g++

# apparently newer xdebug needs these now?
RUN apk add --update linux-headers

RUN if [ -z "${PHP_VERSION##7\.*}" ]; then \
    pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug; \
else \
    pecl install xdebug && docker-php-ext-enable xdebug; \
fi;

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /package

COPY composer.json ./

RUN composer install

COPY . .

RUN composer test
