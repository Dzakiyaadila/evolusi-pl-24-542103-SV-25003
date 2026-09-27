FROM php:8.2-cli-bookworm

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip libonig-dev libsqlite3-dev \
    && docker-php-ext-install mbstring pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

COPY . .
RUN mkdir -p database storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && composer dump-autoload --no-dev --optimize

EXPOSE 8000

CMD ["sh", "-c", "php artisan migrate --force && exec php artisan serve --host=0.0.0.0 --port=8000"]