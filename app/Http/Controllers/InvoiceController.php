<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        // Ensure the user owns the order or has permission
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['item.produk', 'user']);

        return view('invoice.print', compact('order'));
    }
}
