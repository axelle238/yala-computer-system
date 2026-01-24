<?php

namespace App\Livewire\Quotations;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quotation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Detail Quotation - Yala Computer')]
class Show extends Component
{
    public Quotation $quote;

    public function mount($id)
    {
        $this->quote = Quotation::with(['items.product', 'customer', 'sales'])->findOrFail($id);
    }

    public function markAsSent()
    {
        $this->quote->update(['status' => 'sent']);
        session()->flash('success', 'Status updated to Sent.');
    }

    public function markAsAccepted()
    {
        $this->quote->update(['status' => 'accepted']);
        session()->flash('success', 'Status updated to Accepted.');
    }

    public function markAsRejected()
    {
        $this->quote->update(['status' => 'rejected']);
        session()->flash('success', 'Status updated to Rejected.');
    }

    public function convertToOrder()
    {
        if ($this->quote->status === 'converted') {
            return;
        }

        DB::transaction(function () {
            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'guest_name' => $this->quote->customer->name,
                'guest_whatsapp' => $this->quote->customer->phone,
                'total_amount' => $this->quote->total_amount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => 'Converted from Quotation ' . $this->quote->quote_number,
                // 'user_id' could be the sales rep or left null
                'user_id' => auth()->id(), 
            ]);

            // Create Order Items
            foreach ($this->quote->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price,
                ]);
            }

            // Update Quote Status
            $this->quote->update(['status' => 'converted']);
        });

        session()->flash('success', 'Quotation berhasil dikonversi menjadi Order!');
        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.quotations.show');
    }
}
