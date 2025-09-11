FROM php:8.2-fpm-alpine

# Instalar dependencias necesarias
RUN apk add --no-cache \
    zlib-dev \
    libpng-dev \
    oniguruma-dev \
    curl \
    bash \
    git \
    unzip

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar código fuente
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-interaction --no-scripts --no-autoloader \
    && composer dump-autoload --optimize