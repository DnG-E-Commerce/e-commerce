@echo off
php artisan migrate:fresh
pause
php artisan db:seed UserSeeder
pause
php artisan db:seed CategorySeeder
pause
php artisan db:seed ProductSeeder
pause