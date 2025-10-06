<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "📋 Local Database Users (Copy-Paste Format)\n";
echo "==========================================\n\n";

try {
    $users = User::all();
    
    if ($users->count() > 0) {
        echo "// Add these users to AdminUserSeeder.php:\n\n";
        
        foreach ($users as $user) {
            echo "User::create([\n";
            echo "    'name' => '{$user->name}',\n";
            echo "    'email' => '{$user->email}',\n";
            echo "    'password' => Hash::make('YOUR_PASSWORD_HERE'), // You'll need to set this\n";
            echo "    'role' => '{$user->role}',\n";
            echo "    'department_id' => " . ($user->department_id ?? 'null') . ",\n";
            echo "    'employee_id' => " . ($user->employee_id ? "'{$user->employee_id}'" : 'null') . ",\n";
            echo "    'student_id' => " . ($user->student_id ? "'{$user->student_id}'" : 'null') . ",\n";
            echo "    'phone' => " . ($user->phone ? "'{$user->phone}'" : 'null') . ",\n";
            echo "    'is_active' => " . ($user->is_active ? 'true' : 'false') . ",\n";
            echo "    'preferences' => " . json_encode($user->preferences, JSON_PRETTY_PRINT) . ",\n";
            echo "]);\n\n";
        }
    } else {
        echo "❌ No users found in the database!\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Make sure your .env file is configured correctly.\n";
}
