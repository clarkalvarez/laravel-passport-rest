**Laravel Passport Authentication and REST API**

🚀 Introduction
This Laravel project leverages Laravel Passport for API authentication and REST API implementation. Passport provides a comprehensive OAuth2 server implementation, including personal access tokens and password grants.

📋 Requirements
PHP: >= 8
Composer
Laravel: >= 11.x
Database: MySQL

🛠 Installation

```
composer install
```

Copy the .env.example file to .env and update your environment settings:

```
cp .env.example .env
```

Open the .env file and configure your database settings:

```
APP_NAME=local
APP_ENV=local
APP_KEY=
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Generate Application Key

```
php artisan key:generate
```

Install Passport via Composer:

```
composer require laravel/passport
```

Setup Passport tables:

```
php artisan passport:install

Would you like to run all pending database migrations? (yes/no) [yes]: yes
```

Generate Personal Client keys:

```
php artisan passport:client --personal

What should we name the personal access client? [local Personal Access Client]:y
```

Migrate database:

```
php artisan migrate
```

Migrate test database:

```
php artisan migrate --env=testing
```

🛠 Usage

API Docs can be found in http://127.0.0.1:8000/api/documentation

🧪 Testing
Copy the .env.example file to .env.testing and update your environment settings:

```
cp .env.example .env.testing
```

Open the .env.testing file and configure your database settings:

```
APP_NAME=testing
APP_ENV=testing
APP_DEBUG=true
APP_KEY=
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Generate Application Key

```
php artisan key:generate --env=testing
```

Run Tests

```
php artisan test
```
