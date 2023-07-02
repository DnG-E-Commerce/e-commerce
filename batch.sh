#!/bin/bash
php artisan migrate:fresh
php artisan db:seed UserSeeder
php artisan db:seed CategorySeeder
php artisan db:seed ProductSeeder
