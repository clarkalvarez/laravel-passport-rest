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

Authentication Routes

URL: /api/login
Method: POST
Purpose: Login the user to access protected routes
Usage:

```
Body
{
  "email": "test@gmail.com",
  "password": "123456781"
}
Response:
{
  "response_code": "200",
  "status": "success",
  "message": "Login successful.",
  "user_info": {
    "id": 1,
    "name": "test",
    "email": "test@gmail.com",
    "email_verified_at": null,
    "created_at": "2024-08-26T02:08:24.000000Z",
    "updated_at": "2024-08-26T02:08:24.000000Z"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5Y2RhZGMxMS1hOTY1LTRmYTktODVlYi0zZWUxNmU1OTdjYzEiLCJqdGkiOiIzYTZhMGZmMDZiMDM0YThiZjU5MDVmMGJkNWZhNjU1OTZhZmYyNzhhNzcwMjBmMjUwZDdjMjQ1ZTM0OGU0YzFiZmJhMTZkMTQxYjcxMzNkZSIsImlhdCI6MTcyNDYzODEyMi44NDc4NzksIm5iZiI6MTcyNDYzODEyMi44NDc4ODEsImV4cCI6MTc1NjE3NDEyMi43ODk4OTIsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.JGB3fmWrsUUxLjBBKklAWlP1PJCBVcq80NgShx7Xr9q-cguUWewjkaGMztShS_-3kLdm4cGUYHJDtBvNEdxeWa87wCXU_55yt74uPLg6L1YwIWPj0QWxtCdYoTo-q5T0opHuVxhu2-d7LtukNV9AVDcd3nv-nialjYJ-hOIVKdSLPQxQAvCsVFfkWimY6qFI33eLy3ULDfm3Rb67fckfhf2AF4xYWNE_fP3CvwOnF8t03Rn0Oi9Zpb0lb5k1fwKKKwu1GQRkvXdKAPQ_dRaYw5kg-B9ok0z58-_n0tR_kUIix0daJpkm3RjEjKy0Qqp7e1DmWqd50-sFN_S7d01Xwr5EVImH7UHYf4frlLNqzjWqJvsFLfKWFKXQmqHQt9Pw-BOmkB0I_UrYZuTK9Wfs9ZR4MhITuyrBfKzjFeavHwgBDBLKUwDkp53nRBYCJMxH9WTXAlTATOk7l1doBQQ3pOL9rfaE4rNrNtkQSi-V0CWY35-carpiNbisCrGs6LEYfJE6nTkRXL0Bck2nCYzJ8hu3uyEfDBWDS8jU9gRwqKEHj8jul7m2cMSaV5noNKq73QGoY7AcbK7jZ0Syt_X8pi45KK3tun44Y_thUf3RVHQ6EsyIP95qT46xho5k70unlbPK27si8fgon_w4SOUv_heZNqTXenWbD2NzeWobhO8"
}
```

URL: /api/register
Method: POST
Purpose: Register the user
Usage:

```
Body
{
  "name": "test",
  "email": "test@gmail.com",
  "password": "123456781"
}
Response
{
  "response_code": "201",
  "status": "success",
  "message": "User registered successfully."
}
```

🧪 Testing
Copy the .env.example file to .env.testing and update your environment settings:

```
cp .env.example .env.testing
```

Open the .env.testing file and configure your database settings:

```
APP_NAME=testing
APP_ENV=testing
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
