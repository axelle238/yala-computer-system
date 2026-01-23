<?php

namespace App\Http\Middleware;

use App\Models\CashRegister;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashRegisterOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // Cek apakah user punya shift terbuka
        $openRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->exists();

        if (!$openRegister) {
            // Jika mengakses halaman POS, redirect ke halaman Buka Shift
            if ($request->routeIs('transactions.create')) {
                return redirect()->route('shift.open');
            }
        }

        return $next($request);
    }
}