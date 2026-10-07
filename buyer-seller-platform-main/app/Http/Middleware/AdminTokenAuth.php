<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;

class AdminTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $admin = $request->user();

        if (!$admin instanceof AdminUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$admin->is_active) {
            return response()->json(['message' => 'Account disabled'], 403);
        }

        $request->attributes->set('adminUser', $admin);

        return $next($request);
    }
}
