ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-apache

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions mysqli pdo_mysql gd intl zip exif opcache \
    && apt-get update \
    && apt-get install -y --no-install-recommends git unzip mariadb-client \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

ARG WP_CLI_VERSION=2.12.0
RUN curl -fsSL -o /usr/local/bin/wp \
        "https://github.com/wp-cli/wp-cli/releases/download/v${WP_CLI_VERSION}/wp-cli-${WP_CLI_VERSION}.phar" \
    && chmod +x /usr/local/bin/wp

# Apache runs as www-data: give it the uid/gid of the host user, so that it can write in the mounted project
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN groupmod -o -g "${GROUP_ID}" www-data && usermod -o -u "${USER_ID}" -g "${GROUP_ID}" www-data

RUN a2enmod rewrite
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
COPY php.ini /usr/local/etc/php/conf.d/zz-simply.ini

# Caches of the commands run as www-data, whose home directory is not writable
ENV COMPOSER_HOME=/tmp/composer \
    WP_CLI_CACHE_DIR=/tmp/wp-cli

WORKDIR /var/www/project
