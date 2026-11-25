# PHP 7.2: las dependencias fijadas en composer.lock (2018) fallan con PHP 7.4
FROM php:7.2-apache

ARG INSTALL_XDEBUG=false

# Debian buster ya no está en los espejos: se usa un snapshot fijo, con firmas verificadas
ARG DEBIAN_SNAPSHOT=20210115T000000Z
RUN { \
        echo "deb http://snapshot.debian.org/archive/debian/$DEBIAN_SNAPSHOT buster main"; \
        echo "deb http://snapshot.debian.org/archive/debian-security/$DEBIAN_SNAPSHOT buster/updates main"; \
    } > /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false"; Acquire::Retries "5";' > /etc/apt/apt.conf.d/99snapshot \
    && apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libonig-dev libxml2-dev unzip git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# La imagen base ya incluye autoconf, gcc y make
RUN if [ "$INSTALL_XDEBUG" = "true" ]; then pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug; fi

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite \
    && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

WORKDIR /var/www/html

# Composer 1: el lock se generó con esa versión y Laravel 5.6.39 no lee el formato de Composer 2
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-plugins --no-autoloader

COPY . .
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views \
        storage/framework/testing storage/logs bootstrap/cache public/img/brand \
    && composer dump-autoload --optimize --no-plugins --no-scripts \
    && php artisan package:discover \
    && chown -R www-data:www-data storage bootstrap/cache public/img/brand

COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint && chmod +x /usr/local/bin/app-entrypoint

EXPOSE 80
ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]
