<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    // ...

    public function labels(Request $request)
    {
        $key = $request->query('key');
        $queue = Cache::get($key);

        if (!$queue) {
            abort(404, 'Data cetak kadaluarsa.');
        }

        return view('print.labels', compact('queue'));
    }
}
    public function transaction($id)
    {
        // Ambil transaksi utama untuk mendapatkan referensi
        $mainTransaction = InventoryTransaction::with(['product', 'user'])->findOrFail($id);
        
        // Jika ada referensi, ambil semua item dalam satu struk/invoice
        if ($mainTransaction->reference_number && $mainTransaction->reference_number !== '-') {
            $transactions = InventoryTransaction::with(['product'])
                ->where('reference_number', $mainTransaction->reference_number)
                ->get();
        } else {
            // Jika tidak ada referensi (transaksi tunggal/manual), jadikan array tunggal
            $transactions = collect([$mainTransaction]);
        }

        $shopName = Setting::get('shop_name', 'Yala Computer');
        $shopAddress = Setting::get('shop_address', 'Jakarta');
        $shopPhone = Setting::get('whatsapp_number', '-');

        // Pass collection 'transactions' instead of single 'transaction'
        return view('print.transaction', compact('transactions', 'mainTransaction', 'shopName', 'shopAddress', 'shopPhone'));
    }

    public function service($id)
    {
        $ticket = ServiceTicket::findOrFail($id);
        $shopName = Setting::get('shop_name', 'Yala Computer');
        $shopAddress = Setting::get('shop_address', 'Jakarta');
        $shopPhone = Setting::get('whatsapp_number', '-');

        return view('print.service', compact('ticket', 'shopName', 'shopAddress', 'shopPhone'));
    }

    public function productLabel($id)
    {
        $product = Product::findOrFail($id);
        return view('print.label', compact('product'));
    }
}