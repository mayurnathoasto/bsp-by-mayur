<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use Closure;
use Illuminate\Http\Request;

class AdminPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $admin = $request->user();

        if (!$admin instanceof AdminUser) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$admin->hasPermission($permission)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
