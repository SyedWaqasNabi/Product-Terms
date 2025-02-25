# Product Terms

The product-terms microservice is responsible for fetching products from  a PIM system (like Akeneo) and sync it to an E-Commerce platfem (like Magento). Also it fetches price and stock updates from suppliers.

## Container Usage

Run `docker-compose up -d --build`. Open up your browser of choice to [http://product.terms.localhost:8080/](http://product.terms.localhost:8080/) and you should see your Laravel app running as intended. 

Containers created and their ports (if used) are as follows:

- **nginx** - `:8080`
- **mysql** - `:3307`
- **php** - `:9000`

## Application Installation
- Provide custom credentials in `src/.env` file.

For example mysql setting for the docker.
```
DB_HOST=product-terms-mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel_user
DB_PASSWORD=password
```

- Generating Application Key

```
docker-compose run --rm app php artisan key:generate
```

- Start Docker containers.
```
docker-compose up -d
```

- Install dependencies.
```
docker-compose run --rm app composer install
```

- Run database migrations.
```
docker-compose run --rm app php artisan migrate
```

- (Optional) Run database seeds if it's the first time you're setting the project up.
```
docker-compose run --rm app php artisan db:seed
```

- (Optional) Run artisan tinker.
```
docker-compose run --rm app php artisan tinker
```

- (Optional) Run factory data seeds with faker.
```
factory(App\Models\TradeItemOffer::class, 10)->create();
```

## XDebug Installation
To install xDebug you have to set environment argument for app container. By default is enabled.
```
env: development
```

- Enable xDebug
```
docker exec -u root -it product-terms-app /./usr/local/bin/php-xdebug
```

- Disable xDebug
```
docker exec -u root -it product-terms-app /./usr/local/bin/php-xdebug
```

## Development
For easy development you can use the Laravel IDE Helper
```
#Automatic phpDoc generation for Laravel Facades
docker-compose run --rm app php artisan ide-helper:generate

#Automatic phpDocs for all models
docker-compose run --rm app php artisan ide-helper:models

#Automatic phpDocs for specific model by namespace
docker-compose run --rm app php artisan ide-helper:models "App\Models\User"
```

To generate swagger documentation
```
docker-compose run --rm app php artisan l5-swagger:generate
```

Handy commands for copy-pasting.

```
# Dump Composer autoload
docker-compose run --rm app composer dump-autoload

# Fresh migration with seeders
docker-compose run --rm app php artisan migrate:fresh --seed

# Generate Laravel IDE-Helper files for Eloquent models
docker-compose run --rm app php artisan ide-helper:models

# Generate general Laravel IDE-Helper files
docker-compose run --rm app php artisan ide-helper:generate

# Populate keywords table
docker-compose run --rm app php artisan db:seed --class=KeywordsTableSeeder
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
