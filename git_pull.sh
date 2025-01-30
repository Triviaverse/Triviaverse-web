#!/bin/bash

# Redirect all output to tty1
exec > /dev/tty1 2>&1

# Navigate to the project directory
cd ~/Triviaverse-web || exit 1

# Pull the latest code from Git
git fetch --all
git reset --hard origin/main  # Make sure to replace 'main' with your active branch name if different

echo "Running composer update..."
composer update --no-interaction

# Install/update Laravel dependencies
echo "Running composer install..."
composer install --no-interaction

echo "Running migrations..."
php artisan migrate --no-interaction

# Install/update Vue.js dependencies
echo "Running npm install..."
npm install

# Build the frontend assets (optional)
echo "Running npm run build..."
npm run build  # Use 'npm run production' for production builds

# Clear and cache Laravel configuration, routes, and views (optional but recommended)
echo "Clearing and caching Laravel config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart the Laravel queue worker (if applicable)
# systemctl restart laravel-queue-worker.service   # Uncomment if you're using a queue worker service
