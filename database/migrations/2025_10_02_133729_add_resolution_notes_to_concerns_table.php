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
            echo "⚠️ Concerns table does not exist. Skipping resolution notes migration.\n";
            return;
        }
        
        if (!Schema::hasTable('users')) {
            echo "⚠️ Users table does not exist. Skipping resolution notes migration.\n";
            return;
        }

        Schema::table('concerns', function (Blueprint $table) {
            if (!Schema::hasColumn('concerns', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('concerns', 'resolved_by')) {
                $table->unsignedBigInteger('resolved_by')->nullable()->after('resolution_notes');
                $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerns', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn(['resolution_notes', 'resolved_by']);
        });
    }
};
