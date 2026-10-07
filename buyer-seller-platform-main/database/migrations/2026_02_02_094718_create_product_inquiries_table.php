<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_inquiries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Buyer who sent inquiry
            $table->foreignId('buyer_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Seller who receives inquiry (derived from product owner)
            $table->foreignId('seller_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('name')->nullable();   // guest fallback
            $table->string('email')->nullable();  // guest fallback
            $table->string('phone')->nullable();  // guest fallback

            $table->text('message');
            $table->string('status')->default('new')->index(); // new, contacted, closed

            $table->timestamps();

            $table->index(['seller_user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inquiries');
    }
};
