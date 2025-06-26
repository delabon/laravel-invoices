# Laravel invoices

Manages invoices for my personal business (as a remote/freelance developer).

This is a Laravel 12 application and uses SQLite.

### Installation

1. Clone the repository:

```sh
git clone git@github.com:delabon/laravel-invoices.git
cd laravel-invoices
```

2. Build the Docker image:

```bash
docker compose up --build -d
```

3. Install dependencies:

```bash
docker compose exec php-service composer install
```

4. Create the .env file from .env.example:

```bash
docker compose exec php-service cp .env.example .env
```

5. Generate an application key:

```bash
docker compose exec php-service php artisan key:generate
```

6. Run the migration scripts:

```bash
docker compose exec php-service php artisan migrate --step
```

7. Check out the app:

http://localhost/

Telescope: http://localhost/telescope

### Tech Stack & Tools

- Backend: PHP 8.4, Laravel 12
- Database: SQLite
- Testing: Pest
- Static Analysis: Larastan
- Environment Management: Docker
- CI: GitHub actions
- Debug: Telescope

### Testing

To run all tests:

```bash
docker compose exec php-service composer test
```

To run Pest tests:

```bash
docker compose exec php-service composer test:pest
```

To run Larastan/PHPStan tests:

```bash
docker compose exec php-service composer test:stan
```

### License

MIT
