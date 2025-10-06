<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Department;

echo "📤 Exporting Local Database Users\n";
echo "=================================\n\n";

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
        $exportData = [];
        
        foreach ($users as $user) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'employee_id' => $user->employee_id,
                'student_id' => $user->student_id,
                'department_id' => $user->department_id,
                'phone' => $user->phone,
                'is_active' => $user->is_active,
                'preferences' => $user->preferences,
                'created_at' => $user->created_at->toDateTimeString(),
            ];
            
            $exportData[] = $userData;
            
            echo "👤 User: {$user->name} ({$user->email}) - {$user->role}\n";
        }
        
        // Save to JSON file
        $jsonFile = 'exported_users.json';
        file_put_contents($jsonFile, json_encode($exportData, JSON_PRETTY_PRINT));
        
        echo "\n✅ Users exported to: {$jsonFile}\n";
        echo "📋 Copy this file and send it to me so I can add all users to the seeder!\n";
        
        // Also create a PHP array format for easy copying
        $phpFile = 'exported_users.php';
        $phpContent = "<?php\n\n// Exported users from local development\n\$exportedUsers = " . var_export($exportData, true) . ";\n";
        file_put_contents($phpFile, $phpContent);
        
        echo "📋 Also created: {$phpFile} (PHP format)\n";
        
    } else {
        echo "❌ No users found in the database!\n";
    }

    echo "\n✅ Export completed!\n";
    echo "\n📝 Next steps:\n";
    echo "1. Check the exported_users.json file\n";
    echo "2. Send me the contents or copy the users you want\n";
    echo "3. I'll add them all to the AdminUserSeeder.php\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
