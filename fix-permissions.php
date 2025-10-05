<?php

// Fix storage permissions before Laravel starts
$storagePath = '/var/www/html/storage';
$bootstrapPath = '/var/www/html/bootstrap/cache';

// Create directories if they don't exist
$directories = [
    $storagePath,
    $storagePath . '/logs',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/app',
    $storagePath . '/app/public',
    $bootstrapPath,
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    chmod($dir, 0777);
}

// Set ownership to www-data
exec('chown -R www-data:www-data ' . $storagePath . ' ' . $bootstrapPath);

echo "Storage permissions fixed\n";
