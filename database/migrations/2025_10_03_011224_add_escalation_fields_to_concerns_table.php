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

        Schema::table('concerns', function (Blueprint $table) {
            // Only add fields that don't already exist
            if (!Schema::hasColumn('concerns', 'overdue_at')) {
                $table->timestamp('overdue_at')->nullable()->after('escalation_reason');
            }
            if (!Schema::hasColumn('concerns', 'overdue_reason')) {
                $table->text('overdue_reason')->nullable()->after('overdue_at');
            }
            if (!Schema::hasColumn('concerns', 'reassigned_at')) {
                $table->timestamp('reassigned_at')->nullable()->after('overdue_reason');
            }
            if (!Schema::hasColumn('concerns', 'reassigned_by')) {
                $table->string('reassigned_by')->nullable()->after('reassigned_at');
            }
            if (!Schema::hasColumn('concerns', 'reassignment_reason')) {
                $table->text('reassignment_reason')->nullable()->after('reassigned_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerns', function (Blueprint $table) {
            $table->dropColumn([
                'overdue_at',
                'overdue_reason',
                'reassigned_at',
                'reassigned_by',
                'reassignment_reason'
            ]);
        });
    }
};
