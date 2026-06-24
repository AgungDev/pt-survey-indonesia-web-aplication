#!/bin/sh
set -e

cd /var/www/html

if [ -z "$DB_CONNECTION" ]; then
  DB_CONNECTION=pgsql
fi

if [ -z "$DB_HOST" ]; then
  DB_HOST="postgres"
fi

if [ -z "$DB_PORT" ]; then
  DB_PORT=5432
fi

if [ -z "$DB_DATABASE" ]; then
  DB_DATABASE=survey
fi

if [ -z "$DB_USERNAME" ]; then
  DB_USERNAME=survey_user
fi

# Export environment variables for child processes.
export DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD

# Wait for Postgres to become available.
echo "[Entrypoint] Waiting for database ${DB_HOST}:${DB_PORT}..."
until php -r "try { new PDO('pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); } catch (Throwable $e) { exit(1); }" >/dev/null 2>&1; do
  echo "[Entrypoint] Database is unavailable - sleeping"
  sleep 2
done

echo "[Entrypoint] Database is ready"

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
  echo "[Entrypoint] Generating application key"
  php artisan key:generate --force
fi

echo "[Entrypoint] Running database migrations"
php artisan migrate --force
php artisan migrate --force --path=database/migrations/0001_01_09_add_import_history_file_path_column.php

echo "[Entrypoint] Seeding database"
php artisan db:seed --force

echo "[Entrypoint] Starting PHP-FPM"
exec "$@"
