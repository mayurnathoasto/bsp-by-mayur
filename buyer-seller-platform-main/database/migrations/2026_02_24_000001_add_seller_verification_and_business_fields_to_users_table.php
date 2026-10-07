<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Seller verification workflow
            if (!Schema::hasColumn('users', 'seller_status')) {
                // none | pending | approved | rejected
                $table->string('seller_status', 20)->default('none')->after('pincode');
            }
            if (!Schema::hasColumn('users', 'seller_submitted_at')) {
                $table->timestamp('seller_submitted_at')->nullable()->after('seller_status');
            }
            if (!Schema::hasColumn('users', 'seller_verified_at')) {
                $table->timestamp('seller_verified_at')->nullable()->after('seller_submitted_at');
            }
            if (!Schema::hasColumn('users', 'seller_rejection_reason')) {
                $table->text('seller_rejection_reason')->nullable()->after('seller_verified_at');
            }

            // Minimal business profile fields (generic for future country changes)
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name', 180)->nullable()->after('seller_rejection_reason');
            }
            if (!Schema::hasColumn('users', 'company_phone')) {
                $table->string('company_phone', 30)->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('users', 'company_address')) {
                $table->string('company_address', 255)->nullable()->after('company_phone');
            }
            if (!Schema::hasColumn('users', 'tax_id_type')) {
                // GSTIN/VAT/EIN/etc (future-proof)
                $table->string('tax_id_type', 30)->nullable()->after('company_address');
            }
            if (!Schema::hasColumn('users', 'tax_id_number')) {
                $table->string('tax_id_number', 60)->nullable()->after('tax_id_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'tax_id_number',
                'tax_id_type',
                'company_address',
                'company_phone',
                'company_name',
                'seller_rejection_reason',
                'seller_verified_at',
                'seller_submitted_at',
                'seller_status',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};