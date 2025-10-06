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
            echo "⚠️ Announcements table does not exist. Skipping image support migration.\n";
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            // Add image support columns only if they don't exist
            if (!Schema::hasColumn('announcements', 'image_path')) {
                $table->string('image_path')->nullable()->after('content');
            }
            if (!Schema::hasColumn('announcements', 'image_filename')) {
                $table->string('image_filename')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('announcements', 'image_mime_type')) {
                $table->string('image_mime_type')->nullable()->after('image_filename');
            }
            if (!Schema::hasColumn('announcements', 'image_size')) {
                $table->integer('image_size')->nullable()->after('image_mime_type');
            }
            if (!Schema::hasColumn('announcements', 'image_width')) {
                $table->integer('image_width')->nullable()->after('image_size');
            }
            if (!Schema::hasColumn('announcements', 'image_height')) {
                $table->integer('image_height')->nullable()->after('image_width');
            }
            if (!Schema::hasColumn('announcements', 'announcement_type')) {
                $table->enum('announcement_type', ['text', 'image'])->default('text')->after('type');
            }
            
            // Add indexes for better performance (only if they don't exist)
            try {
                $table->index('announcement_type');
                $table->index(['announcement_type', 'status']);
            } catch (Exception $e) {
                // Index might already exist, ignore the error
                echo "Index creation skipped (might already exist)\n";
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['announcement_type']);
            $table->dropIndex(['announcement_type', 'status']);
            $table->dropColumn([
                'image_path',
                'image_filename', 
                'image_mime_type',
                'image_size',
                'image_width',
                'image_height',
                'announcement_type'
            ]);
        });
    }
};
