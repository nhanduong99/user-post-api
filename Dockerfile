# Base image
FROM php:8.2-apache

# Define work directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    git \
    iputils-ping \
    build-essential \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libicu-dev \
    libmemcached-dev \
    default-mysql-client \
    libmagickwand-dev \
    unzip \
    libzip-dev \
    zip \
    libxml2-dev \
    libxslt-dev \
    libssl-dev \
    libldap2-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && docker-php-ext-enable mysqli

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the entire application
COPY . .

# Install project dependencies
RUN composer install

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Configure apache
RUN a2enmod rewrite headers
COPY apache-site.conf /etc/apache2/sites-available/000-default.conf

# RUN cp .env.example .env
# RUN php artisan key:generate
# # RUN php artisan migrate --seed
# RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan cache:clear
# Expose port 8000
EXPOSE 8000

# Start Apache
CMD ["apache2-foreground"]
