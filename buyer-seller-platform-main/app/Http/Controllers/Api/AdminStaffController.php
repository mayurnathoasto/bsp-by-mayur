<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStaffController extends Controller
{
    protected function permissionOptions(): array
    {
        $definitions = AdminUser::permissionDefinitions();

        return collect(AdminUser::permissionGroups())
            ->map(function (array $group) use ($definitions) {
                return [
                    'key' => $group['key'] ?? null,
                    'label' => $group['label'] ?? null,
                    'permissions' => collect($group['permissions'] ?? [])->map(function (string $permission) use ($definitions) {
                        return [
                            'key' => $permission,
                            'label' => $definitions[$permission] ?? $permission,
                        ];
                    })->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    protected function payload(AdminUser $staff): array
    {
        return [
            'id' => $staff->id,
            'name' => $staff->name,
            'email' => $staff->email,
            'mobile' => $staff->mobile,
            'role' => $staff->role,
            'is_active' => (bool) $staff->is_active,
            'permissions' => $staff->resolvedPermissions(),
            'created_at' => optional($staff->created_at)?->toISOString(),
            'creator' => $staff->creator ? [
                'id' => $staff->creator->id,
                'name' => $staff->creator->name,
                'email' => $staff->creator->email,
            ] : null,
        ];
    }

    protected function permissionRule(): array
    {
        return ['array'];
    }

    protected function permissionValueRule(): array
    {
        return ['string', Rule::in(array_keys(AdminUser::permissionDefinitions()))];
    }

    public function index()
    {
        $staff = AdminUser::query()
            ->where('role', AdminUser::ROLE_EMPLOYEE)
            ->with('creator:id,name,email')
            ->latest('id')
            ->get()
            ->map(fn (AdminUser $staffUser) => $this->payload($staffUser))
            ->values();

        return response()->json([
            'data' => $staff,
            'permission_groups' => $this->permissionOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', 'unique:admin_users,email'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'permissions' => $this->permissionRule(),
            'permissions.*' => $this->permissionValueRule(),
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff = AdminUser::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'mobile' => $data['mobile'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => AdminUser::ROLE_EMPLOYEE,
            'permissions' => array_values(array_unique($data['permissions'] ?? [])),
            'created_by' => $request->user()->id,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $staff->load('creator:id,name,email');

        return response()->json([
            'message' => 'Staff user created successfully.',
            'data' => $this->payload($staff),
        ], 201);
    }

    public function update(Request $request, AdminUser $staff)
    {
        if ($staff->role !== AdminUser::ROLE_EMPLOYEE) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', Rule::unique('admin_users', 'email')->ignore($staff->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
            'permissions' => $this->permissionRule(),
            'permissions.*' => $this->permissionValueRule(),
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff->name = $data['name'];
        $staff->email = strtolower($data['email']);
        $staff->mobile = $data['mobile'] ?? null;
        $staff->permissions = array_values(array_unique($data['permissions'] ?? []));
        $staff->is_active = $data['is_active'] ?? $staff->is_active;

        if (!empty($data['password'])) {
            $staff->password = Hash::make($data['password']);
        }

        $staff->save();
        $staff->load('creator:id,name,email');

        return response()->json([
            'message' => 'Staff user updated successfully.',
            'data' => $this->payload($staff),
        ]);
    }

    public function destroy(AdminUser $staff)
    {
        if ($staff->role !== AdminUser::ROLE_EMPLOYEE) {
            abort(404);
        }

        $staff->tokens()->delete();
        $staff->delete();

        return response()->json([
            'message' => 'Staff user deleted successfully.',
        ]);
    }
}
