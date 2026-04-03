#!/bin/bash

echo "=== Writing .env ==="
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

echo "=== .env written ==="
echo "=== PORT=$PORT ==="

cd /var/www/html

echo "=== Clearing config ==="
php artisan config:clear 2>&1

echo "=== Running migrations ==="
php artisan migrate --force 2>&1

echo "=== Configuring Apache ==="
echo "Listen ${PORT:-80}" > /etc/apache2/ports.conf

cat > /etc/apache2/sites-available/000-default.conf << APACHEEOF
<VirtualHost *:${PORT:-80}>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
APACHEEOF

echo "=== Testing Apache config ==="
apache2ctl configtest 2>&1

echo "=== Starting Apache ==="
apache2ctl -D FOREGROUND 2>&1
