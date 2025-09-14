# Use official PHP + Apache image
FROM php:8.2-apache

# Enable required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/
