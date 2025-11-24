# Laravel invoices

Manages invoices for my personal business (as a remote/freelance developer).

### Tech Stack
- PHP 8.4 & Laravel 12
- Web Components: Tailwind 4, Vue 3, Inertia 2, Vite
- DBs: SQLite, Redis (Pub/Sub, Cache, & Queues)
- Testing: Pest 4 (+ browser testing)
- CI/CD: GitHub Actions
- Code Quality: Pint, LaraStan(PHPStan - Level Max)
- Dev tools: Sail (Docker), Telescope, Horizon, Mailpit

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

- App: http://localhost/
- Telescope: http://localhost/telescope
- Horizon: http://localhost/horizon
- Mailpit: http://localhost:8025/

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
vendor/bin/sail composer test:pint
```

### License

MIT
