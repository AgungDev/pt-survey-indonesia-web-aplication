docker compose run --rm app php artisan db:seed --force
docker compose up --build

# ensure DB running
docker compose up -d postgres

# run migrations
docker compose run --rm app php artisan migrate --force

# seed database
docker compose run --rm app php artisan db:seed --force