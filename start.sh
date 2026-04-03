#!/bin/bash
set -e

echo "=== Writing .env ==="
cat > /var/www/html/.env << ENVEOF
APP_NAME="MME Client Portal"
APP_ENV=production
APP_KEY=$APP_KEY
APP_DEBUG=true
APP_URL=$APP_URL
LOG_CHANNEL=errorlog
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

echo "=== Updating Apache port to $PORT ==="
sed -i "s|Listen 80|Listen ${PORT:-80}|g" /etc/apache2/ports.conf
sed -i "s|:80>|:${PORT:-80}>|g" /etc/apache2/sites-available/000-default.conf

echo "=== Running migrations ==="
cd /var/www/html && php artisan config:clear && php artisan migrate --force

echo "=== Starting Apache ==="
exec apache2-foreground
