<?php

namespace App\Livewire\Quotations;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Detail Penawaran - Yala Computer')]
class Show extends Component
{
    public Quotation $quotation;

    public function mount($id)
    {
        $this->quotation = Quotation::with(['items', 'user'])->findOrFail($id);
        
        // Security: Ensure user owns this quote OR is admin
        if (!auth()->user()->hasRole('admin') && $this->quotation->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function acceptQuote()
    {
        if ($this->quotation->approval_status !== 'approved') {
            return;
        }

        if ($this->quotation->converted_order_id) {
             $this->dispatch('notify', message: 'Penawaran ini sudah diproses.', type: 'warning');
             return;
        }

        DB::transaction(function () {
            // Convert to Order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $this->quotation->user_id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'total_amount' => $this->quotation->total_amount,
                'notes' => 'Converted from Quote #' . $this->quotation->quotation_number,
                'shipping_address' => 'Silakan update alamat', // Placeholder
                'shipping_city' => 'Jakarta', // Placeholder
                'shipping_courier' => 'jne',
                'shipping_cost' => 0,
            ]);

            foreach ($this->quotation->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id, // Nullable if custom item?
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                ]);
            }

            $this->quotation->update([
                'converted_order_id' => $order->id,
                'status' => 'converted' // If status enum exists
            ]);

            return $order;
        });

        $this->dispatch('notify', message: 'Penawaran diterima! Pesanan telah dibuat.', type: 'success');
        return redirect()->route('member.orders');
    }

    public function printPdf()
    {
        $this->dispatch('notify', message: 'Fitur Print PDF sedang disiapkan.', type: 'info');
    }

    public function render()
    {
        return view('livewire.quotations.show');
    }
}