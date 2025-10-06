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
        // Check if announcements table exists before trying to modify it
        if (!Schema::hasTable('announcements')) {
            echo "⚠️ Announcements table does not exist. Skipping text fields nullable migration.\n";
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            // Make text-based fields nullable for image-only announcements (only if they exist)
            if (Schema::hasColumn('announcements', 'title')) {
                $table->string('title')->nullable()->change();
            }
            if (Schema::hasColumn('announcements', 'type')) {
                $table->string('type')->nullable()->change();
            }
            if (Schema::hasColumn('announcements', 'priority')) {
                $table->string('priority')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Revert to required fields
            $table->string('title')->nullable(false)->change();
            $table->string('type')->nullable(false)->change();
            $table->string('priority')->nullable(false)->change();
        });
    }
};