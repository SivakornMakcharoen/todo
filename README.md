# Laravel Todo List with Docker

This project contains a Docker setup for a Laravel todo list application using MySQL.

## Services

- `app`: PHP 8.2 application container
- `db`: MySQL 8.0 database container

## Setup

1. Copy the example environment file:

   ```bash
   cp .env.example .env
   ```

2. Build and start containers:

   ```bash
   docker compose up -d --build
   ```

3. If Laravel is not installed yet, create the app with Composer:

   ```bash
   docker compose run --rm app composer create-project laravel/laravel . --prefer-dist
   ```

4. Generate the application key:

   ```bash
   docker compose run --rm app php artisan key:generate
   ```

5. Run migrations:

   ```bash
   docker compose run --rm app php artisan migrate
   ```

6. Open the app in your browser:

   - `http://localhost:8000`
