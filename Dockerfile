# Use a pre-built Laravel image
FROM webdevops/php-apache:8.2

# Set working directory
WORKDIR /app

# Install Composer if not present
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files first
COPY composer.json composer.lock* ./

# Install dependencies with minimal flags
RUN composer install --no-dev --no-scripts --ignore-platform-reqs --no-interaction

# Copy application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Configure Apache document root
ENV WEB_DOCUMENT_ROOT=/app/public

# Generate application key (will be overridden by environment variables)
RUN php artisan key:generate --force

# Expose port
EXPOSE 80
