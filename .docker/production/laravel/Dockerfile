FROM php:8.4-fpm

# Install system dependencies
RUN apt update && apt install -y \
    nginx

# Install Composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install PHP dependencies
#RUN composer install --no-dev --optimize-autoloader

# Optimize Laravel
#RUN php artisan optimize

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/http.d/default.conf

# Expose port (Cloud Run will set the PORT environment variable)
EXPOSE 8080

# Start Nginx and PHP-FPM
CMD sh -c "php-fpm && nginx -g 'daemon off;'"
