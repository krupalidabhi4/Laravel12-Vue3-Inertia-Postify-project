# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install minimal dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Enable mod_rewrite for Laravel
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]