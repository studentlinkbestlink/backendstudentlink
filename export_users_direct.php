<?php

// Direct database connection script (no Laravel required)
$host = '127.0.0.1';
$dbname = 'studentlink_local';
$username = 'root';
$password = 'hellnoway@2025';

echo "🔍 Direct Database Connection\n";
echo "============================\n\n";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to database: $dbname\n\n";
    
    // Get all users
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Found " . count($users) . " users:\n\n";
    
    if (count($users) > 0) {
        echo "// Copy this to AdminUserSeeder.php:\n\n";
        
        foreach ($users as $user) {
            echo "User::create([\n";
            echo "    'name' => '{$user['name']}',\n";
            echo "    'email' => '{$user['email']}',\n";
            echo "    'password' => Hash::make('YOUR_PASSWORD_HERE'), // Set password manually\n";
            echo "    'role' => '{$user['role']}',\n";
            echo "    'department_id' => " . ($user['department_id'] ?? 'null') . ",\n";
            echo "    'employee_id' => " . ($user['employee_id'] ? "'{$user['employee_id']}'" : 'null') . ",\n";
            echo "    'student_id' => " . ($user['student_id'] ? "'{$user['student_id']}'" : 'null') . ",\n";
            echo "    'phone' => " . ($user['phone'] ? "'{$user['phone']}'" : 'null') . ",\n";
            echo "    'is_active' => " . ($user['is_active'] ? 'true' : 'false') . ",\n";
            echo "    'preferences' => " . ($user['preferences'] ? $user['preferences'] : 'null') . ",\n";
            echo "]);\n\n";
        }
        
        // Also save to file
        $exportData = [];
        foreach ($users as $user) {
            $exportData[] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'employee_id' => $user['employee_id'],
                'student_id' => $user['student_id'],
                'department_id' => $user['department_id'],
                'phone' => $user['phone'],
                'is_active' => $user['is_active'],
                'preferences' => $user['preferences'],
            ];
        }
        
        file_put_contents('exported_users.json', json_encode($exportData, JSON_PRETTY_PRINT));
        echo "✅ Also saved to: exported_users.json\n";
        
    } else {
        echo "❌ No users found in the database!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "\n🔧 Troubleshooting:\n";
    echo "1. Make sure MySQL is running\n";
    echo "2. Check if database 'studentlink_prod' exists\n";
    echo "3. Verify username 'studentlink' and password\n";
    echo "4. Try connecting with phpMyAdmin or MySQL Workbench\n";
}
