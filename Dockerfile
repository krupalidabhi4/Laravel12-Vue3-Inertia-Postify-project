FROM php:8.2-apache

WORKDIR /var/www/html

ENV COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip intl bcmath

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

COPY . .

RUN cp .env.example .env || true
RUN php artisan key:generate || true

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]