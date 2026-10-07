<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->after('images');
            });
        }

        DB::table('products')
            ->whereNull('status')
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {
        // keep empty intentionally
    }
};