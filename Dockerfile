FROM php:8.0-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable apache rewrite module
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install

# Change ownership of the files to the web server user
RUN chown -R www-data:www-data /var/www/html
