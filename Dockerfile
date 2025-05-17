FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip curl libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www

COPY . .

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

RUN apt-get update && apt-get install -y sqlite3

RUN composer install
