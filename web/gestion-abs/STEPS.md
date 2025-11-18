1. ./vendor/bin/sail up 
2. connect to the docker container
3. rm -rf vendor composer.lock && composer install
4. ./vendor/bin/sail npm install
5. ./vendor/bin/sail composer require spatie/laravel-permission
6. ./vendor/bin/sail php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
7. ./vendor/bin/sail composer require laravel/sanctum
8. ./vendor/bin/sail php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
9. ./vendor/bin/sail php artisan migrate:refresh
10. ./vendor/bin/sail php artisan db:seed
11. ./vendor/bin/sail npm run  build
12. ./vendor/bin/sail npm run build
13. ./vendor/bin/sail npm run dev



