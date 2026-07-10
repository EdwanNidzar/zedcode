#!/bin/sh
set -e

APP_DIR="/var/www/html"

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  Zedcore — Container Bootstrap"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

cd "$APP_DIR"

# ── 1. Ensure storage directories exist ──────────────────────
echo "[1/6] Creating storage directories..."
mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache

# ── 2. Set file permissions ───────────────────────────────────
echo "[2/6] Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache public/storage
chmod -R 775 storage bootstrap/cache

# ── 3. Create storage symlink (public/storage → storage/app/public)
echo "[3/6] Linking public storage..."
if [ ! -L public/storage ]; then
    php artisan storage:link --force
fi

# ── 4. Cache Laravel configs for performance ─────────────────
echo "[4/6] Caching Laravel config, routes & views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ── 5. Run database migrations ───────────────────────────────
echo "[5/6] Running migrations..."
php artisan migrate --force --no-interaction

# ── 6. Start processes via Supervisor ────────────────────────
echo "[6/6] Starting services (nginx + php-fpm)..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
