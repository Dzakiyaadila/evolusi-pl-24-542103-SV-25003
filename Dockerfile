FROM php:8.2.34-cli-alpine3.23 AS build

WORKDIR /app

RUN apk add --no-cache git unzip oniguruma-dev sqlite-dev $PHPIZE_DEPS \
    && docker-php-ext-install mbstring pdo_sqlite

COPY --from=composer:2.8.12 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --no-progress

COPY . .
RUN mkdir -p database storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && composer dump-autoload --no-dev --optimize

# Tahap 2: hanya menyiapkan lingkungan untuk menjalankan aplikasi
FROM php:8.2.34-cli-alpine3.23 AS runtime

WORKDIR /app

RUN apk add --no-cache oniguruma sqlite-libs \
    && addgroup -S app \
    && adduser -S -G app app

COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=build --chown=app:app /app /app

USER app

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD php -r '$result = @file_get_contents("http://127.0.0.1:8000/api/posts"); exit($result === false ? 1 : 0);'

CMD ["sh", "-c", "php artisan migrate --force && exec php artisan serve --host=0.0.0.0 --port=8000"]