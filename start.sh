#!/bin/sh
set -e

echo "Generating .env..."
cat > /app/.env << EOF
APP_NAME="MME Client Portal"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
LOG_CHANNEL=stack
LOG_LEVEL=error
DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST:-mail.mmedigital.ca}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME:-creative@mmedigital.ca}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-creative@mmedigital.ca}
MAIL_FROM_NAME="MME Digital"
NOTIFICATION_EMAIL=${NOTIFICATION_EMAIL:-creative@mmedigital.ca}
FILESYSTEM_DISK=local
EOF

echo "PORT: ${PORT}"
echo "Clearing config..."
php artisan config:clear

echo "Running migrations..."
php artisan migrate --force --seed

echo "Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
