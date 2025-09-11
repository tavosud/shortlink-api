FROM php:8.2-fpm-alpine

RUN apk add --no-cache --virtual build-essentials zlib-dev libpng-dev

RUN docker-php-ext-install gd pdo pdo_mysql mysqli