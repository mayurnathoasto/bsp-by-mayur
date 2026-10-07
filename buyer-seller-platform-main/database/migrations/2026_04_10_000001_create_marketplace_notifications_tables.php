<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('marketplace_notification_recipients');
        Schema::dropIfExists('marketplace_notifications');

        Schema::create('marketplace_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->default('announcement');
            $table->string('title');
            $table->text('message');
            $table->string('audience', 50)->default('selected_users');
            $table->string('action_url')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('created_by_admin_id')
                ->nullable()
                ->constrained('admin_users')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('marketplace_notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_notification_id')
                ->constrained('marketplace_notifications', indexName: 'mpr_notification_fk')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users', indexName: 'mpr_user_fk')
                ->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['marketplace_notification_id', 'user_id'],
                'marketplace_notification_user_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_notification_recipients');
        Schema::dropIfExists('marketplace_notifications');
    }
};
