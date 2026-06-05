FROM php:8.2-apache

# Install system dependencies and PHP extensions required by CI4 + PostgreSQL
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for CI4 routing
RUN a2enmod rewrite

# Copy Apache configuration
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy entire project
COPY . .

# Install Composer securely
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader

# Create CI4 writable directories and set permissions
RUN mkdir -p /var/www/html/writable/cache \
              /var/www/html/writable/logs \
              /var/www/html/writable/session \
              /var/www/html/writable/uploads \
              /var/www/html/writable/debugbar \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Copy and set executable permissions on startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
