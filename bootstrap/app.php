<?php

use App\Http\Middleware\PreventAccessIfAuthenticated;
use App\Http\Middleware\Role;
use App\Http\Middleware\UpdateLastSeenStatus;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'roles' => Role::class,
            'prevent.authenticated' => PreventAccessIfAuthenticated::class,
        ]);

        $middleware->appendToGroup('web', [
            UpdateLastSeenStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
