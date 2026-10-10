# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# VueFileManager PHP image (php-fpm) used by app, queue, scheduler and reverb.
# ---------------------------------------------------------------------------
FROM php:8.4-fpm-bookworm AS base

# Match the container user with the host user so files created in the
# bind-mounted project directory stay owned by you.
ARG UID=1000
ARG GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip curl \
        default-mysql-client \
        libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev \
        libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath exif gd intl opcache pcntl pdo_mysql pdo_sqlite sockets zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev \
    && apt-get install -y --no-install-recommends libicu72 libzip4 libpng16-16 libjpeg62-turbo libwebp7 libfreetype6 \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupmod -o -g "${GID}" www-data \
    && usermod -o -u "${UID}" -g www-data www-data \
    && mkdir -p /var/www/html /var/www/.composer \
    && chown -R www-data:www-data /var/www

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-vuefilemanager.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-vuefilemanager.conf
COPY --chmod=755 docker/php/entrypoint.sh /usr/local/bin/vfm-entrypoint

ENV COMPOSER_HOME=/var/www/.composer

WORKDIR /var/www/html
USER www-data

ENTRYPOINT ["vfm-entrypoint"]
CMD ["php-fpm"]
