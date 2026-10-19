FROM node:24-bookworm-slim AS frontend
WORKDIR /app
COPY package.json package-lock.json .npmrc ./
RUN npm ci --ignore-scripts
COPY resources ./resources
COPY vite.config.js tsconfig.json ./
RUN npm run build

FROM php:8.3-apache-bookworm AS php-base
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev libonig-dev libxml2-dev libzip-dev libicu-dev unzip \
    && docker-php-ext-install -j2 pdo_pgsql mbstring bcmath intl zip opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer

FROM php-base AS dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY routes ./routes
COPY database ./database
COPY artisan ./artisan
COPY resources/views ./resources/views
RUN composer dump-autoload --no-dev --optimize --no-scripts

FROM php-base AS runtime
ENV APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr
COPY --chown=www-data:www-data . .
COPY --from=dependencies --chown=www-data:www-data /var/www/html/vendor ./vendor
COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/school.ini
COPY docker/mpm-prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY docker/entrypoint.sh /usr/local/bin/school-entrypoint
RUN mkdir -p storage/framework/cache/data storage/framework/cache/locks storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x /usr/local/bin/school-entrypoint
ENTRYPOINT ["/usr/local/bin/school-entrypoint"]
CMD ["apache2-foreground"]
