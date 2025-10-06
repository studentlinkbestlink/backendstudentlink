<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Create departments first (required for users with department_id)
            DepartmentSeeder::class,
            // Create admin and test users
            AdminUserSeeder::class,
            // Create local development users (department heads)
            LocalUsersSeeder::class,
            // Then create staff
            StaffSeeder::class,
            // Create realistic staff data
            RealisticStaffSeeder::class,
            // Update staff capabilities
            UpdateStaffCapabilitiesSeeder::class,
        ]);
    }
}
