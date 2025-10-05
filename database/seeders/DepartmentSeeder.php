<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Handles all IT-related concerns and technical support',
                'head_name' => 'Dr. Maria Santos',
                'head_email' => 'maria.santos@bcp.edu.ph',
                'head_phone' => '09171234567',
                'location' => 'IT Building, 2nd Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Student Affairs',
                'code' => 'SA',
                'description' => 'Manages student services, activities, and welfare',
                'head_name' => 'Mr. Juan Dela Cruz',
                'head_email' => 'juan.delacruz@bcp.edu.ph',
                'head_phone' => '09181234567',
                'location' => 'Student Center, 1st Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Registrar',
                'code' => 'REG',
                'description' => 'Handles enrollment, records, and academic documentation',
                'head_name' => 'Ms. Ana Rodriguez',
                'head_email' => 'ana.rodriguez@bcp.edu.ph',
                'head_phone' => '09191234567',
                'location' => 'Administration Building, 1st Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Manages tuition, fees, and financial transactions',
                'head_name' => 'Mr. Carlos Mendoza',
                'head_email' => 'carlos.mendoza@bcp.edu.ph',
                'head_phone' => '09201234567',
                'location' => 'Administration Building, 2nd Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Library',
                'code' => 'LIB',
                'description' => 'Provides library services and resources',
                'head_name' => 'Ms. Elena Garcia',
                'head_email' => 'elena.garcia@bcp.edu.ph',
                'head_phone' => '09211234567',
                'location' => 'Library Building, 1st Floor',
                'is_active' => true,
            ],
            [
                'name' => 'Guidance and Counseling',
                'code' => 'GC',
                'description' => 'Provides counseling and guidance services',
                'head_name' => 'Dr. Roberto Torres',
                'head_email' => 'roberto.torres@bcp.edu.ph',
                'head_phone' => '09221234567',
                'location' => 'Guidance Office, 1st Floor',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('✅ Created ' . count($departments) . ' departments!');
    }
}
