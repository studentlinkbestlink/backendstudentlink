<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateStaffCapabilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have any staff to update
        $staffCount = \App\Models\User::where('role', 'staff')->count();
        
        if ($staffCount === 0) {
            $this->command->info('No staff found. Skipping staff capabilities update.');
            return;
        }

        $this->command->info("Found {$staffCount} staff members. Updating capabilities...");

        // Update existing staff with cross-department capabilities and titles
        $staffUpdates = [
            // IT Department Staff (can handle cross-department)
            [
                'email' => 'antonio.castillo01@BSIT.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'IT Support Specialist'
            ],
            [
                'email' => 'lourdes.vazquez02@BSIT.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Technical Support Coordinator'
            ],
            [
                'email' => 'teresa.diaz03@BSIT.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'System Administrator'
            ],
            
            // MIS Department Staff (can handle cross-department)
            [
                'email' => 'rosa.garcia01@MIS.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'MIS Support Specialist'
            ],
            [
                'email' => 'maria.navarro02@MIS.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Technical Coordinator'
            ],
            [
                'email' => 'lourdes.romero03@MIS.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'MIS Administrator'
            ],
            
            // CS Department Staff (can handle cross-department)
            [
                'email' => 'ana.reyes01@CS.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'CS Support Specialist'
            ],
            [
                'email' => 'manuel.medina02@CS.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Technical Support'
            ],
            [
                'email' => 'carlos.vega03@CS.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'CS Administrator'
            ],
            
            // Registrar Staff (can handle cross-department)
            [
                'email' => 'fernando.diaz01@REGISTRAR.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Registrar Support'
            ],
            [
                'email' => 'pedro.vega02@REGISTRAR.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Academic Coordinator'
            ],
            [
                'email' => 'elena.peña03@REGISTRAR.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'Registrar Specialist'
            ],
            
            // Cashier Staff (can handle cross-department)
            [
                'email' => 'elena.diaz01@CASHIER.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Financial Support'
            ],
            [
                'email' => 'elena.gil02@CASHIER.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Payment Coordinator'
            ],
            [
                'email' => 'rosa.romero03@CASHIER.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'Cashier Specialist'
            ],
            
            // Discipline Staff (can handle cross-department)
            [
                'email' => 'roberto.peña01@DISCIPLINE.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Discipline Coordinator'
            ],
            [
                'email' => 'patricia.diaz02@DISCIPLINE.bcp.edu.ph',
                'can_handle_cross_department' => true,
                'title' => 'Student Affairs Support'
            ],
            [
                'email' => 'pedro.rodriguez03@DISCIPLINE.bcp.edu.ph',
                'can_handle_cross_department' => false,
                'title' => 'Discipline Officer'
            ],
        ];

        $updatedCount = 0;
        $notFoundCount = 0;

        foreach ($staffUpdates as $update) {
            $staff = User::where('email', $update['email'])->first();
            if ($staff) {
                $staff->update([
                    'can_handle_cross_department' => $update['can_handle_cross_department'],
                    'title' => $update['title']
                ]);
                $this->command->info("Updated staff: {$staff->name} - {$update['title']} (Cross-department: " . 
                     ($update['can_handle_cross_department'] ? 'Yes' : 'No') . ")");
                $updatedCount++;
            } else {
                $this->command->warn("Staff not found: {$update['email']}");
                $notFoundCount++;
            }
        }

        $this->command->info("Staff capabilities update completed: {$updatedCount} updated, {$notFoundCount} not found");

        // Update any remaining staff with default values
        $defaultUpdated = User::where('role', 'staff')
            ->whereNull('can_handle_cross_department')
            ->update([
                'can_handle_cross_department' => false,
                'title' => 'Staff Member'
            ]);

        if ($defaultUpdated > 0) {
            $this->command->info("Set default values for {$defaultUpdated} staff members");
        }
    }
}