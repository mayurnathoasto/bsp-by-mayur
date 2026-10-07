<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Safety: ensure index exists (FK needs an index)
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('user_id');
            }
        });

        // Add index if missing
        $indexExists = collect(Schema::getIndexes('products'))->contains(function ($index) {
            return in_array('category_id', $index['columns']);
        });

        if (!$indexExists) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('category_id');
            });
        }

        // Finally add FK constraint
        Schema::table('products', function (Blueprint $table) {
            // Prevent duplicate FK creation
            // If constraint already exists, this will fail — but your check shows it's not there.
            $table->foreign('category_id', 'fk_products_category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete(); // if a category is deleted, product.category_id becomes null
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // dropForeign expects the index name or array(column)
            try {
                $table->dropForeign(['category_id']);
            } catch (\Throwable $e) {
                // ignore if it doesn't exist
            }
        });
    }
};
