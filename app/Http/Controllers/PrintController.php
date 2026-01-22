<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\Setting;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function transaction($id)
    {
        // Ambil transaksi (kita asumsikan transaksi penjualan adalah 'out')
        // Karena sistem saat ini menyimpan per-item, kita bisa cetak per-item 
        // ATAU idealnya group by Reference Number jika ada.
        // Untuk tahap ini, kita cetak detail transaksi tunggal dulu sebagai "Bukti Transaksi".
        
        $transaction = InventoryTransaction::with(['product', 'user'])->findOrFail($id);
        $shopName = Setting::get('shop_name', 'Yala Computer');
        $shopAddress = Setting::get('shop_address', 'Jakarta');
        $shopPhone = Setting::get('whatsapp_number', '-');

        return view('print.transaction', compact('transaction', 'shopName', 'shopAddress', 'shopPhone'));
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