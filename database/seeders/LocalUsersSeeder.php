<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LocalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating users from local development...');

        // Get departments to use their actual IDs
        $departments = \App\Models\Department::all();
        
        if ($departments->isEmpty()) {
            $this->command->error('No departments found. Please run DepartmentSeeder first.');
            return;
        }

        // Department Heads - map to actual department IDs
        $departmentHeads = [
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'bsais@bcp.edu.ph',
                'role' => 'department_head',
                'department_code' => 'IT', // Information Technology
                'password' => 'department2025',
            ],
            [
                'name' => 'Dr. Roberto Garcia',
                'email' => 'bsbafm@bcp.edu.ph',
                'role' => 'department_head',
                'department_code' => 'SA', // Student Affairs
                'password' => 'department2025',
            ],
            [
                'name' => 'Dr. Patricia Martinez',
                'email' => 'bsbahrm@bcp.edu.ph',
                'role' => 'department_head',
                'department_code' => 'REG', // Registrar
                'password' => 'department2025',
            ],
            [
                'name' => 'Dr. Carlos Rodriguez',
                'email' => 'bsit@bcp.edu.ph',
                'role' => 'department_head',
                'department_code' => 'FIN', // Finance
                'password' => 'department2025',
            ],
        ];

        foreach ($departmentHeads as $user) {
            // Find department by code
            $department = $departments->where('code', $user['department_code'])->first();
            
            if (!$department) {
                $this->command->error("Department with code '{$user['department_code']}' not found. Skipping user: {$user['name']}");
                continue;
            }

            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'role' => $user['role'],
                'department_id' => $department->id,
                'employee_id' => null,
                'student_id' => null,
                'phone' => null,
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
        }

        $this->command->info('✅ Created ' . count($departmentHeads) . ' department heads!');
        $this->command->info('📧 All department heads password: department2025');

        // Note: For the 396 staff members, we'll use the existing StaffSeeder
        // which creates realistic staff data for each department
        $this->command->info('📝 Note: Staff members will be created by StaffSeeder');
    }
}

