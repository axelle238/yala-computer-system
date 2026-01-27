<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::addNamespace('layouts', resource_path('views/layouts'));

        // Gate Global untuk RBAC Yala Computer
        Gate::before(function ($user, $ability) {
            // Jika user memiliki metode punyaAkses, gunakan itu
            if (method_exists($user, 'punyaAkses')) {
                return $user->punyaAkses($ability) ? true : null;
            }
        });
    }
}
