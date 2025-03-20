#!/bin/bash

# Redirect all output to tty1
exec > /dev/tty1 2>&1

# Navigate to the project directory
cd /opt/lampp/htdocs/Triviaverse-web || exit 1

# Pull the latest code from Git
git fetch --all
git reset --hard origin/main  # Make sure to replace 'main' with your active branch name if different

#echo "Updating composer self..."
#/usr/local/bin/composer self-update

#echo "Running composer update..."
#/usr/local/bin/composer update --no-interaction

# Install/update Laravel dependencies
echo "Running composer install..."
/usr/local/bin/composer install --no-interaction

echo "Running migrations..."
php artisan migrate:fresh --seed --no-interaction

# Install/update Vue.js dependencies
echo "Running npm install..."
npm install

# Build the frontend assets (optional)
echo "Running npm run build..."
npm run build  # Use 'npm run production' for production builds

echo "Setting up permissions..."
chmod 777 git_pull.sh monitor_git_changes.sh
chmod -R guo+w storage


# Clear and cache Laravel configuration, routes, and views (optional but recommended)
#echo "Clearing and caching Laravel config, routes, and views..."
#php artisan config:clear
#php artisan route:clear
#php artisan view:clear

#php artisan config:cache
#php artisan route:cache
#php artisan view:cache

