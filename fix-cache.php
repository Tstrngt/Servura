<?php

/**
 * Cache Fix Script
 * Forces file cache and removes all cached configuration
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Force environment variables
$_ENV['CACHE_DRIVER'] = 'file';
$_ENV['SESSION_DRIVER'] = 'file';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Fixing cache configuration...\n";

// Remove all cached files
$cachePaths = [
    __DIR__.'/storage/framework/cache/*',
    __DIR__.'/storage/framework/sessions/*',
    __DIR__.'/storage/framework/views/*',
    __DIR__.'/storage/framework/testing/*',
    __DIR__.'/bootstrap/cache/*'
];

foreach ($cachePaths as $path) {
    $files = glob($path);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            echo "Removed: $file\n";
        }
    }
}

// Clear config cache
$configFile = __DIR__.'/bootstrap/cache/config.php';
if (file_exists($configFile)) {
    unlink($configFile);
    echo "Removed config cache\n";
}

echo "Cache fix completed!\n";
echo "Now run: php artisan config:cache\n";
