<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminPermission;
use App\Http\Middleware\AdminTokenAuth;
use App\Http\Middleware\UserTokenAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.token' => AdminTokenAuth::class,
            'admin.permission' => AdminPermission::class,
            'user.token' => UserTokenAuth::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
