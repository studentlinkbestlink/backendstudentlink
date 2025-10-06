<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Department;

echo "🔍 Checking Local Database Users\n";
echo "================================\n\n";

try {
    // Check if users table exists
    if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
        echo "❌ Users table does not exist!\n";
        exit(1);
    }

    // Get all users
    $users = User::all();
    
    echo "📊 Found {$users->count()} users in local database:\n\n";
    
    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo "👤 User ID: {$user->id}\n";
            echo "   Name: {$user->name}\n";
            echo "   Email: {$user->email}\n";
            echo "   Role: {$user->role}\n";
            echo "   Employee ID: " . ($user->employee_id ?? 'N/A') . "\n";
            echo "   Student ID: " . ($user->student_id ?? 'N/A') . "\n";
            echo "   Department: " . ($user->department_id ? "ID {$user->department_id}" : 'N/A') . "\n";
            echo "   Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
            echo "   Created: {$user->created_at}\n";
            echo "   ---\n";
        }
    } else {
        echo "❌ No users found in the database!\n";
    }

    // Check departments
    echo "\n🏢 Checking Departments:\n";
    if (\Illuminate\Support\Facades\Schema::hasTable('departments')) {
        $departments = Department::all();
        echo "📊 Found {$departments->count()} departments:\n";
        foreach ($departments as $dept) {
            echo "   - {$dept->name} ({$dept->code})\n";
        }
    } else {
        echo "❌ Departments table does not exist!\n";
    }

    echo "\n✅ Database check completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
