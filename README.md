# Laravel invoices

Manages invoices for my personal business (as a remote/freelance developer).

This is a Laravel 12 application and uses SQLite.

### Installation

1. Clone the repository:

```sh
git clone git@github.com:delabon/laravel-invoices.git
cd laravel-invoices
```

2. Setup:

```bash
composer install
vendor/bin/sail up --build -d
cp .env.example .env
vendor/bin/sail artisan key:generate
```

3. Run the migration scripts:

```bash
vendor/bin/sail artisan migrate --step
```

4. Build the assets:

```bash
vendor/bin/sail npm install
vendor/bin/sail npm run build
```

5. Check out the app:

- http://localhost/
- Telescope: http://localhost/telescope

### Tech Stack & Tools

- Backend: PHP 8.4, Laravel 12
- Database: SQLite
- Testing: Pest
- Static Analysis: Larastan
- Code Style Check: Pint
- Environment Management: Sail (Docker)
- CI: GitHub actions
- Debug: Telescope

### Testing

To run all tests:

```bash
vendor/bin/sail composer test
```

To run Pest tests:

```bash
vendor/bin/sail composer test:pest
```

To run Larastan/PHPStan tests:

```bash
vendor/bin/sail composer test:stan
```

To run Pint:

```bash
vendor/bin/sail composer test:lint
```

### License

MIT
