FROM php:8.2-cli

ARG CACHE_BUST=4

RUN apt-get update && apt-get install -y \
    curl zip unzip git \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

RUN chmod +x artisan \
    && mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && cp .env.example .env \
    && composer install --no-dev --optimize-autoloader --no-scripts \
    && php artisan package:discover --ansi \
    && php artisan key:generate --force \
    && php artisan storage:link

EXPOSE 8080

CMD php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan serve --host=0.0.0.0 --port=8080
