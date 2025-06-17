## Laravel invoices

### Up containers

```bash
docker compose up --build -d
```

### Run the following

```bash
docker compose exec php-service composer create-project laravel/laravel .
```

### Build assets

```bash
docker compose exec php-service npm install
docker compose exec php-service npm run build
```

### Run PHPUnit tests

```bash
docker compose exec php-service vendor/bin/phpunit --testsuite=Unit
docker compose exec php-service vendor/bin/phpunit --testsuite=Integration
docker compose exec php-service vendor/bin/phpunit --testsuite=Feature
```

Open http://localhost:8022/ in your browser.
