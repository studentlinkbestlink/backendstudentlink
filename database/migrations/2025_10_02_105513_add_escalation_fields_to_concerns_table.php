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
            echo "⚠️ Concerns table does not exist. Skipping escalation fields migration.\n";
            return;
        }
        
        if (!Schema::hasTable('users')) {
            echo "⚠️ Users table does not exist. Skipping escalation fields migration.\n";
            return;
        }

        Schema::table('concerns', function (Blueprint $table) {
            if (!Schema::hasColumn('concerns', 'escalated_at')) {
                $table->timestamp('escalated_at')->nullable();
            }
            if (!Schema::hasColumn('concerns', 'escalation_level')) {
                $table->string('escalation_level')->nullable(); // staff, department_head, admin
            }
            if (!Schema::hasColumn('concerns', 'escalation_reason')) {
                $table->text('escalation_reason')->nullable();
            }
            if (!Schema::hasColumn('concerns', 'escalated_by')) {
                $table->foreignId('escalated_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('concerns', 'last_reminder_sent')) {
                $table->timestamp('last_reminder_sent')->nullable();
            }
            if (!Schema::hasColumn('concerns', 'ai_classification')) {
                $table->json('ai_classification')->nullable();
            }
            
            // Add indexes only if they don't exist
            try {
                $table->index(['escalated_at']);
                $table->index(['escalation_level']);
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
            $table->dropIndex(['escalated_at']);
            $table->dropIndex(['escalation_level']);
            $table->dropColumn([
                'escalated_at',
                'escalation_level',
                'escalation_reason',
                'escalated_by',
                'last_reminder_sent',
                'ai_classification'
            ]);
        });
    }
};
