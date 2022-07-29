# PET SHOP

API Provider application for PET-SHOP

## Installation

```bash
  cp .env.example .env
```

##### Write your NGINX and PHPMYADMIN PORT in .env and Set username and password for database

```bash 
    sudo docker-compose up -d 
    sudo docker exec -it pet-shop-application bash
    composer install
    php artisan key:generate
    php artisan migrate
```

### Generate Private and Public Keys [ which are already present]

```bash
   ssh-keygen -t rsa -b 4096 -m PEM -f private.pem
   openssl rsa -in private.pem -pubout -outform PEM -out public.pem
```

### update .env

```bash
    JWT_EXPIRES_IN_DAY=3
    JWT_PRIVATE_KEY_PATH=private.pem
    JWT_PUBLIC_KEY_PATH=public.pem
    JWT_PASS_PHRASE=2083
```

### Implemented API ENDPOINTS

```bash
   -Admin
        1. [POST] 
            /api/v1/admin/create
        2. [GET] 
            /api/v1/admin/user-listing
        3. [PUT]  
            /api/v1/admin/user-edit/{users:uuid}
        4. [DELETE]
            /api/v1/admin/user-delete/{users:uuid}
        5. [POST]
            /api/v1/admin/login
        6. [POST]
            /api/v1/admin/logout
   -User
        1. [POST] 
            /api/v1/user/create
        2. [POST] 
            /api/v1/user/login
        3. [POST]  
            /api/v1/user/logut
  -Category
        1. [GET] 
            /api/v1/categories
        2. [POST] 
            /api/v1/category/create
        3. [GET]  
            /api/v1/category/{categories:uuid}
        4. [PUT]  
            /api/v1/category/{categories:uuid}
        5. [DELETE]  
            /api/v1/category/{categories:uuid}
```

### Run Code Analysis

```bash
    php artisan insights
```

### Dev Tools

```bash
    1. PHP CS FIXER
    2. Grum PHP [code format check before commit]
    3. PHP Insights [ code quality analysis]
    4. Symfony Dump Server [dump and debug without effetcing browsers response]
```

