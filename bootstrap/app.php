<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(function (\Illuminate\Routing\Router $router) {
        $router->middleware('web')
            ->group(base_path('routes/web.php'));
        $router->middleware('api')
            ->prefix('api/user')
            ->as('user.')
            ->group(base_path('routes/user.php'));

        $router->middleware('api')
            ->prefix('api/client')
            ->as('client.')
            ->group(base_path('routes/client.php'));

        $router->middleware('api')
            ->prefix('api/admin')
            ->as('admin.')
            ->group(base_path('routes/admin.php'));
    },
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
