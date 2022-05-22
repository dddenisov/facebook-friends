FROM php:8.1

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY ./project /var/www/html

WORKDIR /var/www/html

RUN apt update && apt install -y git zip unzip && docker-php-ext-install pdo pdo_mysql
