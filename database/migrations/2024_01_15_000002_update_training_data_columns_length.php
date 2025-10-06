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
        // Check if training_data table exists before trying to modify it
        if (!Schema::hasTable('training_data')) {
            echo "⚠️ Training_data table does not exist. Skipping column length update migration.\n";
            return;
        }

        Schema::table('training_data', function (Blueprint $table) {
            // Update column lengths to handle longer text (only if columns exist)
            if (Schema::hasColumn('training_data', 'user_message')) {
                $table->text('user_message')->nullable()->change();
            }
            if (Schema::hasColumn('training_data', 'assistant_response')) {
                $table->text('assistant_response')->nullable()->change();
            }
            if (Schema::hasColumn('training_data', 'information')) {
                $table->text('information')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_data', function (Blueprint $table) {
            // Revert to shorter lengths
            $table->string('user_message')->nullable()->change();
            $table->string('assistant_response')->nullable()->change();
            $table->string('information')->nullable()->change();
        });
    }
};
