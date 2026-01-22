<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

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

        if ($isMaintenance && !Auth::check()) {
            // Allow login route
            if ($request->routeIs('login') || $request->routeIs('logout')) {
                return $next($request);
            }
            
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
