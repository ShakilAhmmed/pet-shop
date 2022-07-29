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

### Generate Private and Public Keys

```bash
   ssh-keygen -t rsa -b 4096 -m PEM -f private.pem
   openssl rsa -in private.pem -pubout -outform PEM -out public.pem
```

### Dev Tools

```html
    1. PHP CS FIXER
    2. Grum PHP [code format check before commit] 
```

