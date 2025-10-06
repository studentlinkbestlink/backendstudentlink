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
            echo "⚠️ Announcements table does not exist. Skipping text columns removal migration.\n";
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            // Remove text-based columns that are no longer needed (only if they exist)
            $columnsToDrop = [];
            
            if (Schema::hasColumn('announcements', 'content')) {
                $columnsToDrop[] = 'content';
            }
            if (Schema::hasColumn('announcements', 'excerpt')) {
                $columnsToDrop[] = 'excerpt';
            }
            if (Schema::hasColumn('announcements', 'featured_image')) {
                $columnsToDrop[] = 'featured_image';
            }
            if (Schema::hasColumn('announcements', 'announcement_type')) {
                $columnsToDrop[] = 'announcement_type';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Add back the columns if we need to rollback
            $table->text('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('announcement_type', ['text', 'image'])->default('image');
        });
    }
};