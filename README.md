# Laravel Book Api

## Introduction

This is a Laravel project within imagine assignments to build a book store.

## Installation

1. Clone the repository:

```sh
https://github.com/somarkn99/imagine_book_store_api.git
```

2. Install dependencies:

```php
composer install
```

3. Copy .env.example file to .env file:

```sh
cp .env.example .env
```

4. Generate the application key:

```
php artisan key:generate
```

5. Setup database environment variables in .env file:

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_username_password
```

6. Run migration files and seed the database:

```
php artisan migrate:fresh --seed
```

** Admin User**

```
email: admin@admin.com
password: password
```

7. Run the server:

```
php artisan serve
```

## Usage

There is a postman collection attached at API Doc folder. Content operation end-points with their own tests.

## Running Tests

The project included tests of certain processes.

To run tests, run the following command

```php
  php artisan test
```
