#!/bin/sh
set -e

cd /var/www/html

if [ "${VFM_BOOTSTRAP:-false}" = "true" ]; then
    # The "app" container prepares the project; all other PHP containers wait for it.
    if [ ! -f .env ]; then
        echo "[vfm] Creating .env from docker/.env.docker"
        cp docker/.env.docker .env
    fi

    if [ ! -f vendor/autoload.php ] || [ composer.lock -nt vendor/autoload.php ]; then
        echo "[vfm] Installing composer dependencies"
        composer install --no-interaction --prefer-dist --no-progress
    fi

    if ! grep -q '^APP_KEY=base64:' .env; then
        php artisan key:generate --force
    fi

    mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache

    [ -L public/storage ] || php artisan storage:link || true

    php artisan optimize:clear > /dev/null || true
else
    until [ -f vendor/autoload.php ] && [ -f .env ] && grep -q '^APP_KEY=base64:' .env; do
        echo "[vfm] Waiting for the app container to finish bootstrapping..."
        sleep 3
    done
fi

exec "$@"
