<?php

use App\Models\AdminUser;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $permissionsToAdd = [
            'notifications.manage',
            'support.view',
            'support.reply',
        ];

        AdminUser::query()
            ->where('role', AdminUser::ROLE_EMPLOYEE)
            ->get()
            ->each(function (AdminUser $admin) use ($permissionsToAdd) {
                $current = $admin->permissions ?? [];

                if (!is_array($current)) {
                    $decoded = json_decode((string) $current, true);
                    $current = is_array($decoded) ? $decoded : [];
                }

                $admin->permissions = array_values(array_unique(array_merge($current, $permissionsToAdd)));
                $admin->save();
            });
    }

    public function down(): void
    {
        $permissionsToRemove = [
            'notifications.manage',
            'support.view',
            'support.reply',
        ];

        AdminUser::query()
            ->where('role', AdminUser::ROLE_EMPLOYEE)
            ->get()
            ->each(function (AdminUser $admin) use ($permissionsToRemove) {
                $current = $admin->permissions ?? [];

                if (!is_array($current)) {
                    $decoded = json_decode((string) $current, true);
                    $current = is_array($decoded) ? $decoded : [];
                }

                $admin->permissions = array_values(array_diff($current, $permissionsToRemove));
                $admin->save();
            });
    }
};
