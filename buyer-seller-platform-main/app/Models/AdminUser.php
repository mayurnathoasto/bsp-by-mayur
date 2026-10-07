<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_EMPLOYEE = 'employee';

    protected $table = 'admin_users';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'permissions',
        'created_by',
        'is_active',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function legacyTokens()
    {
        return $this->hasMany(AdminApiToken::class);
    }

    public function creator()
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function staffMembers()
    {
        return $this->hasMany(self::class, 'created_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function resolvedPermissions(): array
    {
        if ($this->isSuperAdmin()) {
            return ['*'];
        }

        return array_values(array_unique(array_filter($this->permissions ?? [])));
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->resolvedPermissions();

        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }

    public static function permissionDefinitions(): array
    {
        return config('admin_permissions.definitions', []);
    }

    public static function permissionGroups(): array
    {
        return config('admin_permissions.groups', []);
    }
}
