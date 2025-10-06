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
            echo "⚠️ Concerns table does not exist. Skipping archived_at migration.\n";
            return;
        }

        Schema::table('concerns', function (Blueprint $table) {
            if (!Schema::hasColumn('concerns', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('disputed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerns', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
    }
};
