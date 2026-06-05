FROM php:8.2-apache

# Install system dependencies and PHP extensions required by CI4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql zip

# Enable Apache mod_rewrite for CI4 routing
RUN a2enmod rewrite

# Copy your custom Apache configuration
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the entire project into the container
COPY . .

# Install Composer securely
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies via Composer (ignoring dev packages)
RUN composer install --no-dev --optimize-autoloader

# Give Apache permissions to write to the CI4 writable directory (logs, cache, sessions)
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Expose the port for Render
EXPOSE 80