FROM php:8.2-apache

WORKDIR /var/www/html

# Fix composer memory
ENV COMPOSER_MEMORY_LIMIT=-1

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl git unzip \
    libonig-dev libzip-dev libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    intl \
    bcmath \
    exif \
    pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first (better caching)
COPY composer.json composer.lock ./

# Install dependencies (IMPORTANT FIX HERE)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --no-scripts

# Copy full project
COPY . .

# Laravel setup (safe for build)
RUN cp .env.example .env || true
RUN php artisan key:generate || true

# Fix permissions
RUN chmod -R 775 storage bootstrap/cache

# Enable Apache rewrite
RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]