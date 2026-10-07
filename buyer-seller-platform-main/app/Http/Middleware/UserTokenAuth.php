<?php

namespace App\Http\Middleware;

use App\Models\UserApiToken;
use Closure;
use Illuminate\Http\Request;

class UserTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = self::resolveTokenFromRequest($request);

        if (!$token || !$token->user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->set('apiUser', $token->user);
        $request->attributes->set('apiUserToken', $token);

        return $next($request);
    }

    public static function resolveTokenFromRequest(Request $request): ?UserApiToken
    {
        $auth = $request->header('Authorization');
        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            return null;
        }

        $plain = trim(substr($auth, 7));
        if ($plain === '') {
            return null;
        }

        $hash = hash('sha256', $plain);

        $token = UserApiToken::with('user')
            ->where('token_hash', $hash)
            ->whereNull('revoked_at')
            ->first();

        if (!$token || !$token->user) {
            return null;
        }

        if ($token->expires_at && now()->greaterThan($token->expires_at)) {
            return null;
        }

        return $token;
    }
}
