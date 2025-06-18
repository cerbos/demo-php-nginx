FROM composer:2 AS composer_stage

RUN rm -rf /var/www && mkdir -p /var/www/html

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev --no-autoloader

RUN composer dump-autoload --optimize --apcu --no-dev

RUN rm composer.json composer.lock

FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add php84-dev --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    && apk add linux-headers \
    && apk add build-base

RUN pecl install grpc \
    && docker-php-ext-enable grpc

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli

COPY --from=composer_stage /var/www/html /var/www/html

COPY src .

CMD ["php-fpm"]

EXPOSE 9000
