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
            echo "⚠️ Concerns table does not exist. Skipping rating migration.\n";
            return;
        }

        Schema::table('concerns', function (Blueprint $table) {
            if (!Schema::hasColumn('concerns', 'rating')) {
                $table->tinyInteger('rating')->nullable()->after('archived_at')->comment('Student rating from 1-5 stars');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerns', function (Blueprint $table) {
            $table->dropColumn('rating');
        });
    }
};
