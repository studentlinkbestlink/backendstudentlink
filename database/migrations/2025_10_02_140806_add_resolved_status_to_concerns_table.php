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
        // Check if concerns table exists before trying to modify it
        if (!Schema::hasTable('concerns')) {
            echo "⚠️ Concerns table does not exist. Skipping resolved status migration.\n";
            return;
        }

        // Check if status column exists before trying to modify it
        if (!Schema::hasColumn('concerns', 'status')) {
            echo "⚠️ Status column does not exist in concerns table. Skipping resolved status migration.\n";
            return;
        }

        // Update status enum (database-agnostic)
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            Schema::table('concerns', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'student_confirmed', 'closed', 'cancelled'])->change();
            });
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: Update constraint
            DB::statement("ALTER TABLE concerns DROP CONSTRAINT IF EXISTS concerns_status_check");
            DB::statement("ALTER TABLE concerns ADD CONSTRAINT concerns_status_check CHECK (status IN ('pending', 'approved', 'in_progress', 'resolved', 'student_confirmed', 'closed', 'cancelled'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status enum (database-agnostic)
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            Schema::table('concerns', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'closed', 'cancelled'])->change();
            });
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: Update constraint
            DB::statement("ALTER TABLE concerns DROP CONSTRAINT IF EXISTS concerns_status_check");
            DB::statement("ALTER TABLE concerns ADD CONSTRAINT concerns_status_check CHECK (status IN ('pending', 'approved', 'in_progress', 'resolved', 'closed', 'cancelled'))");
        }
    }
};
