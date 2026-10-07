<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();

            $table->string('identifier', 150);          // phone or email
            $table->string('identifier_type', 20);      // phone|email
            $table->string('country_code', 2)->default('IN');

            $table->string('otp', 10);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->index(['identifier', 'identifier_type', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
