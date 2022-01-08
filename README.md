# Stock Market Test Project

composer install
cp .env.example .env

php artisan key:generate
--create database --> adcash_test
php artisan migrate

php artisan serve

postman collection located in the root dir [name:adcash.postman_collection.json]

//#
phpunit is a most have for testing ur app
phpunit/phpunit in ur composer.json file

Feaature/Integration test"" a whole request cycle

Unit test should test a small unit of code --> method of a small function

in ur phpunit.xml file,
uncomment the db config inthe file

Sample of Feature Test

set up jest straam to see more test
-- test_registration_screen_can_be_rendered
-- test_new_users_can_register
