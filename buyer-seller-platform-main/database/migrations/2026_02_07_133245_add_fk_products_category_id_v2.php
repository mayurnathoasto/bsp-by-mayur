<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // If FK already exists, do nothing
        $foreignKeys = Schema::getForeignKeys('products');
        $exists = collect($foreignKeys)->contains(function ($fk) {
            return $fk['name'] === 'fk_products_category_id_v2' ||
                (in_array('category_id', $fk['columns']) && $fk['foreign_table'] === 'categories');
        });

        if ($exists) {
            return;
        }

        Schema::table('products', function ($table) {
            $table->foreign('category_id', 'fk_products_category_id_v2')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Drop if exists (MySQL doesn't support IF EXISTS for DROP FOREIGN KEY everywhere)
        Schema::table('products', function ($table) {
            try {
                $table->dropForeign('fk_products_category_id_v2');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};
