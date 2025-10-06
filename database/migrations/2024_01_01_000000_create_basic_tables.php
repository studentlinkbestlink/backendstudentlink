<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        echo "🛡️ Creating Basic Tables\n";
        echo "========================\n\n";

        // 1. DEPARTMENTS TABLE
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 10)->unique();
                $table->text('description')->nullable();
                $table->enum('type', ['academic', 'administrative', 'support'])->default('academic');
                $table->boolean('is_active')->default(true);
                $table->json('contact_info')->nullable();
                $table->timestamps();
                
                $table->index(['type', 'is_active']);
                $table->index('code');
                $table->index('name');
            });
            echo "✅ Created table: departments\n";
        } else {
            echo "⚠️ Table 'departments' already exists\n";
        }

        // 2. USERS TABLE
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('student_id', 20)->unique()->nullable();
                $table->string('employee_id', 20)->unique()->nullable();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('personal_email')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['student', 'department_head', 'admin', 'staff'])->default('student');
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('phone')->nullable();
                $table->string('avatar')->nullable();
                $table->json('preferences')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                
                $table->index(['role', 'department_id']);
                $table->index(['email', 'is_active']);
                $table->index(['personal_email', 'is_active']);
                $table->index('student_id');
                $table->index('employee_id');
                $table->index(['is_active', 'role']);
            });
            echo "✅ Created table: users\n";
        } else {
            echo "⚠️ Table 'users' already exists\n";
        }

        // 3. Add foreign key constraint after both tables exist
        if (Schema::hasTable('departments') && Schema::hasTable('users')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
                });
                echo "✅ Added foreign key constraint: users.department_id -> departments.id\n";
            } catch (Exception $e) {
                echo "⚠️ Could not add foreign key constraint: " . $e->getMessage() . "\n";
            }
        }

        echo "\n🎉 Basic tables created successfully!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments');
    }
};
