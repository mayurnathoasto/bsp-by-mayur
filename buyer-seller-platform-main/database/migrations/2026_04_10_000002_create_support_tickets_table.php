<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('target_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('type', 20)->default('ticket');
            $table->string('status', 30)->default('open');
            $table->string('order_reference')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('context_url')->nullable();
            $table->text('admin_reply')->nullable();
            $table->foreignId('admin_replied_by')
                ->nullable()
                ->constrained('admin_users')
                ->nullOnDelete();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
