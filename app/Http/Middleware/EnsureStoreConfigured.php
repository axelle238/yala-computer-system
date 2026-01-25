<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreConfigured
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah nama toko sudah diatur (indikator awal konfigurasi)
        // Kita gunakan cache sederhana atau query langsung karena Setting model biasanya di-cache
        $storeName = Setting::where('key', 'store_name')->value('value');

        if (empty($storeName)) {
            // Jika user adalah admin, arahkan ke settings
            if ($request->user() && $request->user()->isAdmin()) {
                // Hindari redirect loop jika sudah di halaman settings
                if (! $request->routeIs('settings.*')) {
                    return redirect()->route('settings.index')
                        ->with('error', 'Harap lengkapi konfigurasi toko (Nama Toko) sebelum melanjutkan operasional.');
                }
            } else {
                // Untuk customer/guest, tampilkan error 503 Service Unavailable sementara
                abort(503, 'Toko sedang dalam konfigurasi awal.');
            }
        }

        return $next($request);
    }
}
