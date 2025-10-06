<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bcp.edu.ph',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'department_id' => null,
            'employee_id' => 'ADMIN-001',
            'phone' => '09171234567',
            'is_active' => true,
            'preferences' => [
                'theme' => 'light',
                'language' => 'en',
                'notifications' => [
                    'email' => true,
                    'push' => true,
                    'sms' => false
                ]
            ]
        ]);

        // Create test staff user
        User::create([
            'name' => 'Test Staff',
            'email' => 'staff@bcp.edu.ph',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'department_id' => 1, // IT Department
            'employee_id' => 'IT-001',
            'phone' => '09181234567',
            'is_active' => true,
            'preferences' => [
                'theme' => 'light',
                'language' => 'en',
                'notifications' => [
                    'email' => true,
                    'push' => true,
                    'sms' => false
                ]
            ]
        ]);

        // Note: Department heads are created by LocalUsersSeeder to avoid duplicates

        $this->command->info('✅ Created admin and test users!');
        $this->command->info('📧 Admin: admin@bcp.edu.ph / admin123');
        $this->command->info('📧 Staff: staff@bcp.edu.ph / staff123');
        $this->command->info('📝 Note: Department heads are created by LocalUsersSeeder');
    }
}
