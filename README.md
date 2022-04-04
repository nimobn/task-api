clone the repository
composer update
cp .env.example .env
php artisan key:generate
config database in .env file
php artisan migrate
php artisan passport:install
php artisan serve
