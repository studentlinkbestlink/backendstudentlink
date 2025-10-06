<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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

        // 2. USERS TABLE - Handle both cases (new creation and existing table)
        if (!Schema::hasTable('users')) {
            // Create new users table with all columns
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
            echo "⚠️ Table 'users' already exists - adding missing columns\n";
            
            // Add missing columns to existing users table
            if (!Schema::hasColumn('users', 'student_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('student_id', 20)->unique()->nullable();
                });
                echo "✅ Added column: student_id\n";
            }
            
            if (!Schema::hasColumn('users', 'employee_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('employee_id', 20)->unique()->nullable();
                });
                echo "✅ Added column: employee_id\n";
            }
            
            if (!Schema::hasColumn('users', 'personal_email')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('personal_email')->nullable();
                });
                echo "✅ Added column: personal_email\n";
            }
            
            if (!Schema::hasColumn('users', 'role')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->enum('role', ['student', 'department_head', 'admin', 'staff'])->default('student');
                });
                echo "✅ Added column: role\n";
            }
            
            if (!Schema::hasColumn('users', 'department_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('department_id')->nullable();
                });
                echo "✅ Added column: department_id\n";
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('phone')->nullable();
                });
                echo "✅ Added column: phone\n";
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('avatar')->nullable();
                });
                echo "✅ Added column: avatar\n";
            }
            
            if (!Schema::hasColumn('users', 'preferences')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->json('preferences')->nullable();
                });
                echo "✅ Added column: preferences\n";
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true);
                });
                echo "✅ Added column: is_active\n";
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('last_login_at')->nullable();
                });
                echo "✅ Added column: last_login_at\n";
            }
        }

        // 3. Add foreign key constraint after both tables exist and department_id column exists
        if (Schema::hasTable('departments') && Schema::hasTable('users') && Schema::hasColumn('users', 'department_id')) {
            try {
                // Check if foreign key constraint already exists
                $foreignKeys = DB::select("
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE table_name = 'users' 
                    AND constraint_type = 'FOREIGN KEY'
                    AND constraint_name LIKE '%department_id%'
                ");
                
                if (empty($foreignKeys)) {
                    Schema::table('users', function (Blueprint $table) {
                        $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
                    });
                    echo "✅ Added foreign key constraint: users.department_id -> departments.id\n";
                } else {
                    echo "✅ Foreign key constraint already exists\n";
                }
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
