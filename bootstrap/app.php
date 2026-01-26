<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin*')) {
                return route('masuk');
            }
            return route('pelanggan.masuk');
        });

        $middleware->append(\App\Http\Middleware\CheckForMaintenanceMode::class);
        $middleware->alias([
            'shift.open' => \App\Http\Middleware\EnsureCashRegisterOpen::class,
            'store.configured' => \App\Http\Middleware\EnsureStoreConfigured::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
