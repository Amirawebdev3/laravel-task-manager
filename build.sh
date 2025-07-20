#!/bin/bash

# Install PHP and Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
export PATH="$PATH:$HOME/.composer/vendor/bin"

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate app key if missing
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chmod -R 775 storage bootstrap/cache