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
        // Check if required tables exist before creating chat_messages with foreign keys
        if (!Schema::hasTable('concerns')) {
            echo "⚠️ Concerns table does not exist. Skipping chat messages migration.\n";
            return;
        }
        
        if (!Schema::hasTable('users')) {
            echo "⚠️ Users table does not exist. Skipping chat messages migration.\n";
            return;
        }

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concern_id')->constrained()->onDelete('cascade');
            
            // Only add foreign key to chat_rooms if that table exists
            if (Schema::hasTable('chat_rooms')) {
                $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('chat_room_id');
            }
            
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'file', 'system', 'status_change', 'resolution_confirmation', 'resolution_dispute', 'chat_closure', 'chat_reopened'])->default('text');
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_typing')->default(false);
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->json('reactions')->nullable();
            
            // Self-referencing foreign key - will be added after table creation
            $table->unsignedBigInteger('reply_to_id')->nullable();
            
            $table->timestamps();

            $table->index(['chat_room_id', 'created_at']);
            $table->index(['concern_id', 'created_at']);
            $table->index(['author_id', 'created_at']);
            $table->index('message_type');
        });
        
        // Add self-referencing foreign key after table creation
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->foreign('reply_to_id')->references('id')->on('chat_messages')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};