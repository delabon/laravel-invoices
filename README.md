## Laravel invoices

### Up containers

```bash
docker compose up --build -d
```

### Run the following

```bash
composer create-project laravel/laravel .
```

### Build assets

```bash
docker compose run --rm node-service npm install
docker compose run --rm node-service npm run build
```

### Run PHPUnit tests

```bash
docker compose exec php-service vendor/bin/phpunit --testsuite=Unit
docker compose exec php-service vendor/bin/phpunit --testsuite=Integration
docker compose exec php-service vendor/bin/phpunit --testsuite=Feature
```

Open http://localhost:8022/ in your browser.
