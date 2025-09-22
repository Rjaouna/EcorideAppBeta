FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl zip gd \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimise le cache Docker
COPY composer.json composer.lock* ./
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction || true

COPY . .
RUN composer install --prefer-dist --no-progress --no-interaction

# Permissions Symfony
RUN mkdir -p var && chown -R www-data:www-data var
USER www-data

CMD ["php-fpm"]
