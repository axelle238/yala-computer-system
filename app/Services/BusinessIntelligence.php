<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BusinessIntelligence
{
    public function getInsights()
    {
        $insights = [];

        // 1. Deteksi Produk Terlaris (Trending)
        $trending = InventoryTransaction::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->where('type', 'out')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        if ($trending) {
            $insights[] = [
                'type' => 'trend',
                'icon' => 'fire',
                'color' => 'text-orange-500 bg-orange-50',
                'title' => 'Produk Trending',
                'message' => "{$trending->product->name} sangat laku minggu ini ({$trending->total_qty} terjual). Pastikan stok aman!",
            ];
        }

        // 2. Deteksi Dead Stock (Barang tidak laku 30 hari terakhir)
        // Cari produk yang stoknya > 0 tapi tidak ada transaksi 'out' dalam 30 hari
        $deadStock = Product::where('stock_quantity', '>', 0)
            ->whereDoesntHave('transactions', function($q) {
                $q->where('type', 'out')
                  ->where('created_at', '>=', Carbon::now()->subDays(30));
            })
            ->inRandomOrder()
            ->first();

        if ($deadStock) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'archive',
                'color' => 'text-slate-500 bg-slate-100',
                'title' => 'Potensi Dead Stock',
                'message' => "{$deadStock->name} belum terjual dalam 30 hari. Pertimbangkan untuk membuat promo diskon.",
            ];
        }

        // 3. Prediksi Restock (Stok menipis vs Laju Penjualan)
        // Cari produk dengan stok < 5 dan ada penjualan minggu ini
        $critical = Product::where('stock_quantity', '<=', 5)
            ->where('is_active', true)
            ->first();

        if ($critical) {
            $insights[] = [
                'type' => 'alert',
                'icon' => 'exclamation',
                'color' => 'text-rose-500 bg-rose-50',
                'title' => 'Stok Kritis',
                'message' => "{$critical->name} tersisa {$critical->stock_quantity}. Segera lakukan Restock (PO) ke Supplier.",
            ];
        }

        return $insights;
    }
}
