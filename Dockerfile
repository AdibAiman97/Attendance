FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libxslt-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install xml \
    && docker-php-ext-install xsl \
    && docker-php-ext-install zip \
    && docker-php-ext-install soap \
    && docker-php-ext-install mysqli

# Enable apache modules
RUN a2enmod rewrite && a2enmod env

# Configure PHP to expose environment variables
RUN echo "variables_order = \"EGPCS\"" >> /usr/local/etc/php/conf.d/docker-php-env.ini

# Create Apache config to pass environment variables to PHP
RUN echo "PassEnv DB_HOST DB_USER DB_PASSWORD DB_NAME DB_PORT" > /etc/apache2/conf-available/render-env.conf && \
    a2enconf render-env

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
