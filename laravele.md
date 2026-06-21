docker compose run --rm app php artisan db:seed --force
docker compose up --build

# ensure DB + redis running
docker compose up -d postgres redis

# run migrations
docker compose run --rm app php artisan migrate --force

# seed database
docker compose run --rm app php artisan db:seed --force