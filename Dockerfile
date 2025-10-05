# Use a pre-built Laravel image with PHP 8.2 and Apache
FROM webdevops/php-apache:8.2

# Set working directory
WORKDIR /var/www/html

# Install Composer if not present
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a fresh Laravel project
RUN composer create-project laravel/laravel temp-laravel --prefer-dist --no-dev --no-interaction
RUN cp -r temp-laravel/* . && rm -rf temp-laravel

# Try to install packages one by one with fallbacks
RUN composer require tymon/jwt-auth:^2.0 --no-dev --ignore-platform-reqs --no-interaction || \
    (echo "JWT install failed, trying alternative..." && composer require tymon/jwt-auth --no-dev --ignore-platform-reqs --no-interaction)

RUN composer require twilio/sdk:^7.0 --no-dev --ignore-platform-reqs --no-interaction || \
    (echo "Twilio install failed, trying alternative..." && composer require twilio/sdk --no-dev --ignore-platform-reqs --no-interaction)

# Copy only essential application files
COPY app/ app/
COPY config/ config/
COPY routes/ routes/
COPY database/ database/
COPY public/ public/
COPY fix-permissions.php ./

# Create a basic .env file
RUN echo "APP_NAME=StudentLink" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_URL=https://backendstudentlink.onrender.com" >> .env && \
    echo "LOG_CHANNEL=syslog" >> .env && \
    echo "LOG_LEVEL=error" >> .env

# Create storage directories with proper permissions
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/app/public \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/storage/framework

# Configure Apache
RUN a2enmod rewrite
COPY .docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Application key will be set via environment variables in Render

# Create startup script
RUN echo '#!/bin/bash\n\
# Fix permissions first\n\
php /var/www/html/fix-permissions.php\n\
\n\
# Set proper permissions aggressively\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 777 /var/www/html/storage\n\
chmod -R 777 /var/www/html/bootstrap/cache\n\
\n\
# Ensure storage directories exist\n\
mkdir -p /var/www/html/storage/logs\n\
mkdir -p /var/www/html/storage/framework/cache\n\
mkdir -p /var/www/html/storage/framework/sessions\n\
mkdir -p /var/www/html/storage/framework/views\n\
chmod -R 777 /var/www/html/storage\n\
\n\
# Generate application key if not set\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Run database migrations\n\
php artisan migrate --force\n\
\n\
# Run database seeders\n\
php artisan db:seed --force\n\
\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 80

# Start with our custom script
CMD ["/usr/local/bin/start.sh"]
