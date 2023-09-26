## How to run

Copy .env.example as .env file and fill DB_* keys
Run:

```
php artisan db:wipe
php artisan migrate
php artisan db:seed
php artisan jwt:secret
php artisan serve
```

## How to run test

```
php artisan db:wipe
php artisan migrate
php artisan db:seed
php vendor/bin/codecept run Api
```

## TODO

- swagger
- unit tests for services
- better pagination information on responses
- json response schema validation in codeception
- better model serialization
