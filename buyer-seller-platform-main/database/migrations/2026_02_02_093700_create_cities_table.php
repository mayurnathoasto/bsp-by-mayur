<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            // India-only for Phase 1, but future-ready if you add more countries later
            $table->string('country_code', 2)->default('IN')->index();

            $table->string('name')->index();
            $table->string('state')->nullable()->index();

            // For Phase 1 we store a "primary" pincode (you can expand to multiple pincodes later)
            $table->string('pincode', 10)->nullable()->index();

            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['country_code', 'name', 'state'], 'cities_country_name_state_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
