<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('lead_time_value')->nullable()->after('min_order_quantity');
            $table->string('lead_time_unit', 50)->nullable()->after('lead_time_value');

            $table->json('shipping_options')->nullable()->after('lead_time_unit');
            $table->json('payment_options')->nullable()->after('shipping_options');

            $table->unsignedInteger('sample_moq')->nullable()->after('payment_options');
            $table->string('warranty', 255)->nullable()->after('sample_moq');
            $table->string('support_option', 100)->nullable()->after('warranty');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'lead_time_value',
                'lead_time_unit',
                'shipping_options',
                'payment_options',
                'sample_moq',
                'warranty',
                'support_option',
            ]);
        });
    }
};