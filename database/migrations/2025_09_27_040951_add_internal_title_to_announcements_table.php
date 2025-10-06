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
            echo "⚠️ Announcements table does not exist. Skipping internal title migration.\n";
            return;
        }

        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'internal_title')) {
                $table->string('internal_title')->nullable()->after('author_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('internal_title');
        });
    }
};
