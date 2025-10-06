<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if users table exists before trying to modify it
        if (!Schema::hasTable('users')) {
            echo "⚠️ Users table does not exist. Skipping cross department capability migration.\n";
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'can_handle_cross_department')) {
                $table->boolean('can_handle_cross_department')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'title')) {
                $table->string('title')->nullable()->after('can_handle_cross_department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_handle_cross_department', 'title']);
        });
    }
};
