## Project setup/installation guides
Please Follow the guideline to set up locally.

- Laravel 11 (php 8.3/8.2)

### Installation process after cloning from git

1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. set database mysql and update related things in .env (for example your database name, password)
5. php artisan migrate
6. php artisan db:seed
7. php artisan generate:admin
8. for social login set this value in .env

    "FACEBOOK_CLIENT_ID=
    FACEBOOK_CLIENT_SECRET=
    FACEBOOK_CALLBACK_URL=http://127.0.0.1:8000/api/v1/auth/facebook/callback
    
    GOOGLE_CLIENT_ID=
    GOOGLE_CLIENT_SECRET=
    GOOGLE_CALLBACK_URL=http://127.0.0.1:8000/api/v1/auth/google/callback"

9. php artisan queue:work for products CSV import (Attached products.csv file example in project directory)

# note:
I also added postman collection name as CurlWare.postman_collection.json in this main directory.
