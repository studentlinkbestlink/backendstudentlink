<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if concerns table exists before modifying
        if (Schema::hasTable('concerns')) {
            // Add new columns for student-controlled resolution
            Schema::table('concerns', function (Blueprint $table) {
                // Add student resolution tracking
                if (!Schema::hasColumn('concerns', 'student_resolved_at')) {
                    $table->timestamp('student_resolved_at')->nullable()->after('resolved_at');
                }
                if (!Schema::hasColumn('concerns', 'student_resolution_notes')) {
                    $table->text('student_resolution_notes')->nullable()->after('student_resolved_at');
                }
                if (!Schema::hasColumn('concerns', 'dispute_reason')) {
                    $table->text('dispute_reason')->nullable()->after('student_resolution_notes');
                }
                if (!Schema::hasColumn('concerns', 'disputed_at')) {
                    $table->timestamp('disputed_at')->nullable()->after('dispute_reason');
                }
            });

            // Update the status enum to include new resolution statuses (database-agnostic)
            if (Schema::hasColumn('concerns', 'status')) {
                $driver = DB::getDriverName();
                
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE concerns MODIFY COLUMN status ENUM(
                        'pending', 
                        'approved', 
                        'rejected', 
                        'in_progress', 
                        'staff_resolved', 
                        'student_confirmed', 
                        'disputed', 
                        'closed', 
                        'cancelled'
                    ) DEFAULT 'pending'");
                } elseif ($driver === 'pgsql') {
                    // PostgreSQL: Update constraint
                    DB::statement("ALTER TABLE concerns DROP CONSTRAINT IF EXISTS concerns_status_check");
                    DB::statement("ALTER TABLE concerns ADD CONSTRAINT concerns_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'in_progress', 'staff_resolved', 'student_confirmed', 'disputed', 'closed', 'cancelled'))");
                    DB::statement("ALTER TABLE concerns ALTER COLUMN status SET DEFAULT 'pending'");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the new columns
        if (Schema::hasTable('concerns')) {
            Schema::table('concerns', function (Blueprint $table) {
                $table->dropColumn([
                    'student_resolved_at',
                    'student_resolution_notes', 
                    'dispute_reason',
                    'disputed_at'
                ]);
            });

            // Revert the status enum to original values (database-agnostic)
            if (Schema::hasColumn('concerns', 'status')) {
                $driver = DB::getDriverName();
                
                if ($driver === 'mysql') {
                    DB::statement("ALTER TABLE concerns MODIFY COLUMN status ENUM(
                        'pending', 
                        'approved', 
                        'rejected', 
                        'in_progress', 
                        'resolved', 
                        'closed', 
                        'cancelled'
                    ) DEFAULT 'pending'");
                } elseif ($driver === 'pgsql') {
                    // PostgreSQL: Update constraint
                    DB::statement("ALTER TABLE concerns DROP CONSTRAINT IF EXISTS concerns_status_check");
                    DB::statement("ALTER TABLE concerns ADD CONSTRAINT concerns_status_check CHECK (status IN ('pending', 'approved', 'rejected', 'in_progress', 'resolved', 'closed', 'cancelled'))");
                    DB::statement("ALTER TABLE concerns ALTER COLUMN status SET DEFAULT 'pending'");
                }
            }
        }
    }
};
