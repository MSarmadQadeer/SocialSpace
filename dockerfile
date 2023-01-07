FROM php:8.1

RUN apt-get update && apt-get install -y openssl zip unzip git zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp

RUN docker-php-ext-install pdo pdo_mysql gd

WORKDIR /app

COPY . .

RUN composer install

RUN cp .env.docker-example .env

RUN php artisan key:generate
