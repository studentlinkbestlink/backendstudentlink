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

# Create a minimal Laravel app structure
RUN composer create-project laravel/laravel temp-laravel --prefer-dist --no-dev
RUN cp -r temp-laravel/* . && rm -rf temp-laravel

# Copy only essential application files
COPY app/ app/
COPY config/ config/
COPY routes/ routes/
COPY database/ database/
COPY public/ public/
COPY .env.example .env

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
