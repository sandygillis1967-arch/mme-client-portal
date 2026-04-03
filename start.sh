#!/bin/sh
echo "=== CONTAINER STARTED ==="
echo "PORT=$PORT"
echo "DB_HOST=$DB_HOST"
echo "APP_KEY=$APP_KEY"

# Write .env
cat > /app/.env << ENVEOF
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
ENVEOF

echo "=== .env written ==="
php artisan config:clear
php artisan migrate --force
echo "=== Starting server on $PORT ==="
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
