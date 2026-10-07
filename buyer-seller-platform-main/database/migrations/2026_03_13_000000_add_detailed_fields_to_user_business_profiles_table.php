<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_business_profiles', function (Blueprint $table) {
            // Additional Details Fields
            $table->string('logo')->nullable();
            $table->json('company_photos')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_title')->nullable();
            $table->string('video_category')->nullable();
            $table->json('payment_modes')->nullable();
            $table->text('excluded_cities')->nullable();
            $table->string('working_days_from')->nullable();
            $table->string('working_days_to')->nullable();
            $table->string('working_time_from')->nullable();
            $table->string('working_time_to')->nullable();
            $table->text('preferred_cities')->nullable();
            $table->text('preferred_states')->nullable();
            $table->text('company_description')->nullable();

            // Certification Fields
            $table->string('standard_certification')->nullable();
            $table->string('membership_affiliations')->nullable();
            $table->string('award_recognition')->nullable();
            $table->json('certifications')->nullable();
            $table->json('awards')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_business_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'logo', 'company_photos', 'video_url', 'video_title', 'video_category',
                'payment_modes', 'excluded_cities', 'working_days_from', 'working_days_to',
                'working_time_from', 'working_time_to', 'preferred_cities', 'preferred_states',
                'company_description', 'standard_certification', 'membership_affiliations',
                'award_recognition', 'certifications', 'awards'
            ]);
        });
    }
};
