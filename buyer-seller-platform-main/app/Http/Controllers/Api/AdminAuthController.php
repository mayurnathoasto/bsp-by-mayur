<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    protected function adminPayload(AdminUser $admin): array
    {
        return [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'mobile' => $admin->mobile,
            'role' => $admin->role,
            'is_active' => (bool) $admin->is_active,
            'permissions' => $admin->resolvedPermissions(),
        ];
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string','min:4'],
        ]);

        $email = strtolower($data['email']);
        $primaryAdminEmail = strtolower((string) env('ADMIN_PRIMARY_EMAIL', 'admin@buyersellerplatform.com'));

        $adminCount = AdminUser::count();

        if ($adminCount === 0) {
            if ($email !== $primaryAdminEmail) {
                return response()->json(['message' => 'Invalid credentials'], 403);
            }

            // First login: auto-create primary admin
            $admin = AdminUser::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($data['password']),
                'role' => AdminUser::ROLE_ADMIN,
                'is_active' => true,
            ]);
        } else {
            $admin = AdminUser::where('email', $email)->first();
            if (!$admin || !Hash::check($data['password'], $admin->password)) {
                return response()->json(['message' => 'Invalid credentials'], 400);
            }
            if (!$admin->is_active) {
                return response()->json(['message' => 'Account disabled'], 403);
            }
        }

        // Keep one active admin panel token per admin and expire it after 1 day.
        $admin->tokens()->delete();
        $plain = $admin->createToken('admin-panel', ['admin'], now()->addDay())->plainTextToken;

        return response()->json([
            'accessToken' => $plain,
            'admin' => $this->adminPayload($admin),
        ]);
    }

    public function me(Request $request)
    {
        $admin = $request->user();

        return response()->json([
            'admin' => $this->adminPayload($admin),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $admin = $request->user();

        $data = $request->validate([
            'mobile' => ['nullable','string','max:20'],
            'currentPassword' => ['nullable','string'],
            'newPassword' => ['nullable','string','min:4'],
        ]);

        if (array_key_exists('mobile', $data)) {
            $admin->mobile = $data['mobile'];
        }

        if (!empty($data['newPassword'])) {
            if (empty($data['currentPassword'])) {
                return response()->json(['message' => 'Current password required to set new password'], 400);
            }
            if (!Hash::check($data['currentPassword'], $admin->password)) {
                return response()->json(['message' => 'Incorrect current password'], 400);
            }
            $admin->password = Hash::make($data['newPassword']);
        }

        $admin->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'admin' => $this->adminPayload($admin),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
