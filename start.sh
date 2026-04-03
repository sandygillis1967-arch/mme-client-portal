#!/bin/bash

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
ENVEOF

cd /var/www/html
php artisan config:clear
php artisan migrate --force

echo "Starting server on port ${PORT:-80}"
php -S 0.0.0.0:${PORT:-80} -t public
