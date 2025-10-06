<?php
// StudentLink Backend - PHP Detection File
// This file helps Render detect this as a PHP project

echo "StudentLink Backend - PHP Project Detected\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Project Type: Laravel Backend\n";
echo "Status: Ready for deployment\n";

// Redirect to Laravel's public directory
if (file_exists(__DIR__ . '/public/index.php')) {
    require_once __DIR__ . '/public/index.php';
} else {
    echo "Laravel public directory not found. Please check your deployment.\n";
}
