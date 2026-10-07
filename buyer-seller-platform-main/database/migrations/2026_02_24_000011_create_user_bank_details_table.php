<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_bank_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->string('account_type', 30)->nullable(); // Savings/Current etc
            $table->string('account_holder_name', 120)->nullable();
            $table->string('account_number', 60)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('linked_mobile', 30)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bank_details');
    }
};