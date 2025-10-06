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
        // Update the role enum to include 'staff' (database-agnostic)
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            $driver = DB::getDriverName();
            
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'staff', 'department_head', 'admin') DEFAULT 'student'");
            } elseif ($driver === 'pgsql') {
                // PostgreSQL: Drop and recreate the enum type
                DB::statement("ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(20)");
                DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'student'");
                DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('student', 'staff', 'department_head', 'admin'))");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the role enum to exclude 'staff' (database-agnostic)
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            $driver = DB::getDriverName();
            
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'department_head', 'admin') DEFAULT 'student'");
            } elseif ($driver === 'pgsql') {
                // PostgreSQL: Update constraint to exclude 'staff'
                DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
                DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('student', 'department_head', 'admin'))");
            }
        }
    }
};
