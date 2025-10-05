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
            // Create admin and test users first
            AdminUserSeeder::class,
            // Create departments
            DepartmentSeeder::class,
            // Then create staff
            StaffSeeder::class,
            // Create realistic staff data
            RealisticStaffSeeder::class,
            // Update staff capabilities
            UpdateStaffCapabilitiesSeeder::class,
        ]);
    }
}
