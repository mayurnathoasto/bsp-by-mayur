<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add nullable column first (no unique yet)
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_identifier')->nullable()->after('id');
        });

        // 2) Backfill existing users
        // Prefer phone, else email. If both missing, set a guaranteed-unique placeholder.
        DB::table('users')->whereNull('login_identifier')->orderBy('id')->chunkById(200, function ($users) {
            foreach ($users as $u) {
                $identifier = null;

                if (!empty($u->phone)) {
                    $identifier = 'P:' . trim($u->phone);
                } elseif (!empty($u->email)) {
                    $identifier = 'E:' . strtolower(trim($u->email));
                } else {
                    // Fallback unique value so migration doesn't fail
                    $identifier = 'U:' . $u->id;
                }

                DB::table('users')->where('id', $u->id)->update([
                    'login_identifier' => $identifier,
                ]);
            }
        });

        // 3) Now add unique index (safe)
        Schema::table('users', function (Blueprint $table) {
            $table->unique('login_identifier');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['login_identifier']);
            $table->dropColumn('login_identifier');
        });
    }
};
