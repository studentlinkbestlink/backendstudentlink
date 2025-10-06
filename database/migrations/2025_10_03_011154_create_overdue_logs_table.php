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
        // Check if required tables exist before creating overdue_logs with foreign keys
        if (!Schema::hasTable('concerns')) {
            echo "⚠️ Concerns table does not exist. Skipping overdue logs migration.\n";
            return;
        }
        
        if (!Schema::hasTable('users')) {
            echo "⚠️ Users table does not exist. Skipping overdue logs migration.\n";
            return;
        }

        Schema::create('overdue_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concern_id')->constrained()->onDelete('cascade');
            $table->string('overdue_type')->default('automated'); // automated, manual
            $table->text('overdue_reason');
            $table->string('detected_by'); // user_id or 'system'
            $table->timestamp('detected_at');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
            
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overdue_logs');
    }
};
