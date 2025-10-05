<?php
// fix-permissions.php - COMPREHENSIVE PERMISSION FIX
// This script ensures that Laravel's storage directories exist and have proper permissions.

echo "🔧 COMPREHENSIVE PERMISSION FIX SCRIPT\n";
echo "=====================================\n\n";

$basePath = '/var/www/html';
$storagePath = $basePath . '/storage';
$bootstrapCachePath = $basePath . '/bootstrap/cache';

// Define all required directories
$directories = [
    $storagePath,
    $storagePath . '/logs',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/app',
    $storagePath . '/app/public',
    $bootstrapCachePath,
];

echo "📁 Creating and setting permissions for directories...\n";

foreach ($directories as $dir) {
    echo "Processing: {$dir}\n";
    
    // Create directory if it doesn't exist
    if (!is_dir($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "  ✅ Created directory: {$dir}\n";
        } else {
            echo "  ❌ Failed to create directory: {$dir}\n";
            continue;
        }
    } else {
        echo "  📂 Directory already exists: {$dir}\n";
    }
    
    // Set permissions
    if (chmod($dir, 0777)) {
        echo "  ✅ Set permissions to 0777: {$dir}\n";
    } else {
        echo "  ❌ Failed to set permissions: {$dir}\n";
    }
}

// Set ownership (if running as root)
echo "\n👤 Setting ownership to www-data...\n";
exec('chown -R www-data:www-data ' . $basePath . ' 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "  ✅ Ownership set successfully\n";
} else {
    echo "  ⚠️ Could not set ownership (return code: {$returnCode})\n";
}

// Final verification
echo "\n🔍 Final verification...\n";
foreach ($directories as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "  ✅ {$dir} - OK\n";
    } else {
        echo "  ❌ {$dir} - NOT WRITABLE\n";
    }
}

echo "\n🎉 Permission fix script completed!\n";
echo "=====================================\n";
?>
