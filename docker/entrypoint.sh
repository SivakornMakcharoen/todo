#!/usr/bin/env sh
set -eu

if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

mkdir -p \
  bootstrap/cache \
  storage/app/private \
  storage/app/public \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/testing \
  storage/framework/views \
  storage/logs

if [ -f artisan ]; then
  if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    php artisan key:generate --force --no-interaction
  fi

  if [ "${DB_CONNECTION:-}" = "mysql" ]; then
    until mysqladmin ping --skip-ssl -h"${DB_HOST:-db}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME:-root}" -p"${DB_PASSWORD:-}" --silent; do
      sleep 2
    done
  fi

  php artisan config:clear --no-interaction
  php artisan migrate --force --no-interaction
fi

echo "Starting application: $*"
exec "$@"
