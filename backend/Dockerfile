FROM php:8.3-fpm

ARG USER=www-data
ARG UID=1000
ARG GID=1000

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    cron \
    && docker-php-ext-install pdo pdo_pgsql \
    && pecl install redis \
    && docker-php-ext-enable redis opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

RUN usermod -u ${UID} ${USER} && groupmod -g ${GID} ${USER} || true \
    && chown -R ${USER}:${USER} /var/www/html/storage /var/www/html/bootstrap/cache

USER ${USER}

CMD ["php-fpm"]

