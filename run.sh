#!/bin/bash

php artisan serve &
php artisan queue:listen &
php artisan websockets:serve
