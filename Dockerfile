# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json ./

# Install only essential Laravel dependencies (skip problematic packages)
RUN composer require --no-dev --ignore-platform-reqs \
    laravel/framework:^10.48 \
    laravel/sanctum:^3.3 \
    vlucas/phpdotenv:^5.5 \
    symfony/console:^6.4 \
    symfony/http-foundation:^6.4 \
    symfony/http-kernel:^6.4 \
    symfony/routing:^6.4 \
    symfony/process:^6.4 \
    symfony/var-dumper:^6.4 \
    monolog/monolog:^3.4 \
    nesbot/carbon:^2.71 \
    ramsey/uuid:^4.7 \
    guzzlehttp/guzzle:^7.8 \
    twilio/sdk:^7.0

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Apache
RUN a2enmod rewrite
COPY .docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Generate application key (will be overridden by environment variables)
RUN php artisan key:generate --force

# Expose port
EXPOSE 80
