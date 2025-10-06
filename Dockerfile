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
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create a fresh Laravel project
RUN composer create-project laravel/laravel temp-laravel --prefer-dist --no-dev --no-interaction
RUN cp -r temp-laravel/* . && rm -rf temp-laravel

# Copy composer.json only (no lock file to avoid version conflicts)
COPY composer.json ./

# Install ALL dependencies - let Composer resolve versions fresh
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --ignore-platform-reqs --no-interaction

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

# Create storage directories with proper permissions - AGGRESSIVE APPROACH
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/app/public \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/storage/framework \
    && chmod -R 777 /var/www/html/storage/app \
    && chmod -R 777 /var/www/html/storage/app/public

# Configure Apache
RUN a2enmod rewrite
COPY .docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Application key will be set via environment variables in Render

# Create comprehensive startup script
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Starting StudentLink Backend..."\n\
\n\
# Step 1: Fix permissions COMPLETELY\n\
echo "📁 Setting up storage permissions..."\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 777 /var/www/html/storage\n\
chmod -R 777 /var/www/html/bootstrap/cache\n\
chmod -R 777 /var/www/html/storage/logs\n\
chmod -R 777 /var/www/html/storage/framework\n\
chmod -R 777 /var/www/html/storage/app\n\
\n\
# Step 2: Ensure all directories exist\n\
echo "📂 Creating storage directories..."\n\
mkdir -p /var/www/html/storage/logs\n\
mkdir -p /var/www/html/storage/framework/cache\n\
mkdir -p /var/www/html/storage/framework/sessions\n\
mkdir -p /var/www/html/storage/framework/views\n\
mkdir -p /var/www/html/storage/app/public\n\
chmod -R 777 /var/www/html/storage\n\
\n\
# Step 3: Generate application key\n\
echo "🔑 Generating application key..."\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Step 4: Verify JWT package\n\
echo "📦 Verifying JWT package..."\n\
if composer show tymon/jwt-auth >/dev/null 2>&1; then\n\
    echo "  ✅ JWT package is installed"\n\
else\n\
    echo "  ❌ JWT package is missing - this will cause errors"\n\
fi\n\
\n\
# Step 5: Run database migrations\n\
echo "🗄️ Running database migrations..."\n\
php artisan migrate --force\n\
\n\
# Step 6: Run database seeders\n\
echo "🌱 Running database seeders..."\n\
php artisan db:seed --force\n\
\n\
# Step 7: Final permission check\n\
echo "✅ Final permission check..."\n\
chmod -R 777 /var/www/html/storage\n\
chmod -R 777 /var/www/html/bootstrap/cache\n\
\n\
echo "🎉 Backend setup complete! Starting Apache..."\n\
\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 80

# Start with our custom script
CMD ["/usr/local/bin/start.sh"]
