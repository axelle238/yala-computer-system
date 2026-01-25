<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isMaintenance = (bool) Setting::get('maintenance_mode', false);

        if ($isMaintenance && ! Auth::check()) {
            // Allow login route and Livewire internal routes
            if ($request->routeIs('login') || $request->routeIs('logout') || $request->is('livewire/*')) {
                return $next($request);
            }

            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
