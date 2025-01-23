# Use the official PHP image with Nginx and Composer
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel files into the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-scripts --no-autoloader

# Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
