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

        Schema::table('concerns', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'student_confirmed', 'closed', 'cancelled'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerns', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'in_progress', 'resolved', 'closed', 'cancelled'])->change();
        });
    }
};
