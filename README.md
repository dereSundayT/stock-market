# Stock Market Test Project

composer install
cp .env.example .env

php artisan key:generate
--create database --> adcash_test
php artisan migrate

php artisan serve

postman collection located in the root dir [name:adcash.postman_collection.json]
