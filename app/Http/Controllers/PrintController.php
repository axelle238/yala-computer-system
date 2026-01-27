<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrintController extends Controller
{
    public function shippingLabel($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        $shopName = Setting::get('store_name', 'Yala Computer');
        $shopAddress = Setting::get('store_address', 'Jakarta'); // Corrected key based on Settings seeder/form
        $shopPhone = Setting::get('store_phone', '-'); // Corrected key

        return view('print.shipping_label', compact('order', 'shopName', 'shopAddress', 'shopPhone'));
    }

    public function labels(Request $request)
    {
        $queue = Session::get('label_print_queue', []);

        if (empty($queue)) {
            return redirect()->back()->with('error', 'Antrian cetak kosong.');
        }

        $items = Product::whereIn('id', array_keys($queue))->get()->map(function ($product) use ($queue) {
            $product->print_qty = $queue[$product->id];

            return $product;
        });

        $type = $request->query('type', 'price_tag');
        $paper = $request->query('paper', 'a4');

        return view('print.labels', compact('items', 'type', 'paper'));
    }

    public function transaction($id)
    {
        $mainTransaction = InventoryTransaction::with(['product', 'user'])->findOrFail($id);

        if ($mainTransaction->reference_number && $mainTransaction->reference_number !== '-') {
            $transactions = InventoryTransaction::with(['product'])
                ->where('reference_number', $mainTransaction->reference_number)
                ->get();
        } else {
            $transactions = collect([$mainTransaction]);
        }

        $shopName = Setting::get('store_name', 'Yala Computer');
        $shopAddress = Setting::get('address', 'Jakarta');
        $shopPhone = Setting::get('whatsapp_number', '-');

        return view('print.transaction', compact('transactions', 'mainTransaction', 'shopName', 'shopAddress', 'shopPhone'));
    }

    public function service($id)
    {
        $ticket = ServiceTicket::findOrFail($id);
        $shopName = Setting::get('store_name', 'Yala Computer');
        $shopAddress = Setting::get('address', 'Jakarta');
        $shopPhone = Setting::get('whatsapp_number', '-');

        return view('print.service', compact('ticket', 'shopName', 'shopAddress', 'shopPhone'));
    }

    public function productLabel($id)
    {
        $product = Product::findOrFail($id);

        // Single item print fallback
        return view('print.labels', [
            'items' => collect([$product])->map(fn ($p) => $p->print_qty = 1),
            'type' => 'price_tag',
            'paper' => 'thermal_100x150',
        ]);
    }
}
