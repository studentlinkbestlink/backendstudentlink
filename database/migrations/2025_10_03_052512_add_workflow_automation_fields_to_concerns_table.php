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
            echo "⚠️ Concerns table does not exist. Skipping workflow automation fields migration.\n";
            return;
        }

        Schema::table('concerns', function (Blueprint $table) {
            // Auto-approval fields
            if (!Schema::hasColumn('concerns', 'auto_approved')) {
                $table->boolean('auto_approved')->default(false)->after('rating');
            }
            
            // Auto-closure fields (closed_at already exists, so only add missing ones)
            if (!Schema::hasColumn('concerns', 'closed_by')) {
                $table->string('closed_by')->nullable()->after('closed_at');
            }
            if (!Schema::hasColumn('concerns', 'auto_closed')) {
                $table->boolean('auto_closed')->default(false)->after('closed_by');
            }
            
            // Indexes for better performance (only if they don't exist)
            try {
                $table->index(['auto_approved', 'created_at']);
                $table->index(['escalated_at', 'escalated_by']);
                $table->index(['closed_at', 'auto_closed']);
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
        Schema::table('concerns', function (Blueprint $table) {
            $table->dropIndex(['auto_approved', 'created_at']);
            $table->dropIndex(['escalated_at', 'escalated_by']);
            $table->dropIndex(['closed_at', 'auto_closed']);
            
            $table->dropColumn([
                'auto_approved',
                'closed_by',
                'auto_closed',
            ]);
        });
    }
};