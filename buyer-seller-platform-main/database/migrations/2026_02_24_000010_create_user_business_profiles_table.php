<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_business_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Company basics
            $table->string('company_name', 180)->nullable();
            $table->string('company_phone', 30)->nullable();
            $table->string('fax_number', 30)->nullable();

            // Business meta
            $table->string('ownership_type', 40)->nullable(); // Manufacturer/Trader/Wholesaler/Distributor/Importer/Exporter
            $table->string('annual_turnover_currency', 10)->nullable(); // INR etc
            $table->decimal('annual_turnover_amount', 14, 2)->nullable();
            $table->string('annual_turnover_unit', 20)->nullable(); // Lakh/Crore/Million etc
            $table->integer('year_established')->nullable();
            $table->integer('employee_count')->nullable();

            // Address (manual)
            $table->string('address_line', 255)->nullable();

            // India: city dropdown (optional)
            $table->unsignedBigInteger('city_id')->nullable();

            // Fallback text fields
            $table->string('city_name', 120)->nullable();
            $table->string('state_name', 120)->nullable();
            $table->string('country_code', 5)->nullable(); // IN/AE/US...
            $table->string('pincode', 20)->nullable();

            // Google Places-ready fields (future)
            $table->string('place_id', 120)->nullable();
            $table->string('formatted_address', 255)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // Tax + documents (future-proof)
            $table->json('tax_ids_json')->nullable();
            $table->json('documents_json')->nullable();

            $table->timestamps();

            // Optional FK to cities table if you want strict relation:
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_business_profiles');
    }
};