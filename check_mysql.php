<?php

echo "🔍 MySQL Connection Test\n";
echo "=======================\n\n";

// Try different common MySQL configurations
$configs = [
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'password'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'password'],
];

foreach ($configs as $config) {
    echo "Trying: {$config['user']}@{$config['host']} (password: " . ($config['pass'] ? 'YES' : 'NO') . ")\n";
    
    try {
        $pdo = new PDO("mysql:host={$config['host']};charset=utf8mb4", $config['user'], $config['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ SUCCESS! Connected to MySQL\n";
        
        // List databases
        $stmt = $pdo->query("SHOW DATABASES");
        $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "📊 Available databases:\n";
        foreach ($databases as $db) {
            echo "   - $db\n";
        }
        
        // Check if studentlink_prod exists
        if (in_array('studentlink_prod', $databases)) {
            echo "\n🎯 Found 'studentlink_prod' database!\n";
            
            // Connect to the specific database
            $pdo = new PDO("mysql:host={$config['host']};dbname=studentlink_prod;charset=utf8mb4", $config['user'], $config['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check if users table exists
            $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
            if ($stmt->rowCount() > 0) {
                echo "✅ Users table exists!\n";
                
                // Count users
                $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                $count = $stmt->fetchColumn();
                echo "📊 Found $count users in the database\n";
                
                // Get first few users as sample
                $stmt = $pdo->query("SELECT id, name, email, role FROM users LIMIT 5");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "\n👥 Sample users:\n";
                foreach ($users as $user) {
                    echo "   - {$user['name']} ({$user['email']}) - {$user['role']}\n";
                }
                
                echo "\n✅ Ready to export users!\n";
                echo "Use this configuration in your .env file:\n";
                echo "DB_HOST={$config['host']}\n";
                echo "DB_USERNAME={$config['user']}\n";
                echo "DB_PASSWORD={$config['pass']}\n";
                echo "DB_DATABASE=studentlink_prod\n";
                
            } else {
                echo "❌ Users table does not exist\n";
            }
        } else {
            echo "\n❌ 'studentlink_prod' database not found\n";
        }
        
        break; // Stop on first successful connection
        
    } catch (PDOException $e) {
        echo "❌ Failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "\n💡 If none worked, make sure:\n";
echo "1. MySQL server is running (XAMPP, WAMP, or standalone)\n";
echo "2. Check your MySQL configuration\n";
echo "3. Try connecting with phpMyAdmin first\n";

