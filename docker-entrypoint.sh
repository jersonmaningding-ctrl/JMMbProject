#!/bin/bash
set -e

# 1. Gumawa ng .env mula sa environment variables kung wala pa
if [ ! -f .env ]; then
    echo "Creating .env from environment variables..."
    cat > .env <<EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

BROADCAST_DRIVER="${BROADCAST_DRIVER:-log}"
CACHE_DRIVER="${CACHE_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
EOF
fi

# 2. I-generate ang APP_KEY kung wala pa sa .env at environment
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=.\+" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# 3. I-cache ang configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. I-migrate ang database
php artisan migrate --force

# 5. Gumawa ng admin user kung may credentials
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    echo "Seeding admin user..."
    php artisan db:seed --class=AdminOnlySeeder --force
fi

# 6. Ayusin ulit ang permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 7. Simulan ang server (safe na quoted ang port)
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"