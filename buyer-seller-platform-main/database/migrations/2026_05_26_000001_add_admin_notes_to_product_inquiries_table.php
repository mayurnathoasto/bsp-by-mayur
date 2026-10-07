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
        if (Schema::hasTable('product_inquiries')) {
            Schema::table('product_inquiries', function (Blueprint $table) {
                if (!Schema::hasColumn('product_inquiries', 'admin_notes')) {
                    $table->text('admin_notes')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_inquiries')) {
            Schema::table('product_inquiries', function (Blueprint $table) {
                if (Schema::hasColumn('product_inquiries', 'admin_notes')) {
                    $table->dropColumn('admin_notes');
                }
            });
        }
    }
};
