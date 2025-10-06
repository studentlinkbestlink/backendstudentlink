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
        // Check if otp_verifications table exists before trying to modify it
        if (!Schema::hasTable('otp_verifications')) {
            echo "⚠️ OTP verifications table does not exist. Skipping metadata migration.\n";
            return;
        }

        Schema::table('otp_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('otp_verifications', 'metadata')) {
                $table->json('metadata')->nullable()->after('failed_attempts');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
