# blog-app

This project is developed by Mohidul Islam Shovon.

## Project Setup
```sh
composer install
cp .\.env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```

## Test
```sh
php artisan test
```
