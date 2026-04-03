#!/bin/bash

# Write .env from Railway environment variables
cat > /var/www/html/.env << ENVEOF
APP_NAME="MME Client Portal"
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=true
APP_URL=$APP_URL
LOG_CHANNEL=stderr
DB_CONNECTION=mysql
DB_HOST=$DB_HOST
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=cookie
FILESYSTEM_DISK=local
NOTIFICATION_EMAIL=${NOTIFICATION_EMAIL:-creative@mmedigital.ca}
ENVEOF

cd /var/www/html
php artisan config:clear
php artisan migrate --force

# Run PHP built-in server directly - no Apache
php -S 0.0.0.0:${PORT:-80} public/index.php
