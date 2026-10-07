<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'alternate_email')) {
                $table->string('alternate_email', 180)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'office_contact')) {
                $table->string('office_contact', 30)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'alternate_mobile')) {
                $table->string('alternate_mobile', 30)->nullable()->after('office_contact');
            }
            if (!Schema::hasColumn('users', 'whatsapp_number')) {
                $table->string('whatsapp_number', 30)->nullable()->after('alternate_mobile');
            }
            if (!Schema::hasColumn('users', 'designation')) {
                $table->string('designation', 120)->nullable()->after('whatsapp_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['designation', 'whatsapp_number', 'alternate_mobile', 'office_contact', 'alternate_email'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};