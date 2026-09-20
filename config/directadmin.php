<?php

return [
    'url' => env('DIRECTADMIN_URL'),
    'username' => env('DIRECTADMIN_USERNAME'),
    'password' => env('DIRECTADMIN_PASSWORD'),
    'verify_ssl' => env('DIRECTADMIN_VERIFY_SSL', true),
    'timeout' => (int) env('DIRECTADMIN_TIMEOUT', 20),
    'shared_ip' => env('DIRECTADMIN_SHARED_IP'),
];
