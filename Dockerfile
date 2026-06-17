# Use PHP 8.4 with Apache
FROM php:8.4-apache

# Install system dependencies (added netcat for database readiness check)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    netcat-openbsd

# Clean cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions for Laravel and MySQL
RUN docker-php-ext-install pdo_mysql mbstring pcntl bcmath gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# # Set Apache DocumentRoot to Laravel's public folder
# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set Apache DocumentRoot to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# === ថែម ២ ជួរនេះ ដើម្បីបង្ខំឱ្យ Apache ផ្លាស់ប្ដូររន្ធ Port ទៅតាម Render ទាំងក្នុង ports.conf និង vhost ===
RUN sed -s -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -s -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy entire project
COPY . .

RUN rm -f .env bootstrap/cache/*.php

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Build frontend with npm/vite
RUN npm install && npm run build

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and make entrypoint script executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80
# EXPOSE 80
EXPOSE 10000

# Run entrypoint script when container starts
ENTRYPOINT ["docker-entrypoint.sh"]
