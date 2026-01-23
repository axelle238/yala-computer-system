<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Checkout - Yala Computer')]
class Checkout extends Component
{
    public $name;
    public $whatsapp;
    public $notes;
    public $cartItems = [];

    public function mount()
    {
        $this->cartItems = session()->get('cart', []);
        if (empty($this->cartItems)) {
            return redirect()->route('cart');
        }

        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->whatsapp = auth()->user()->phone ?? ''; 
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
        ]);

        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Generate Unique Order Number
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'user_id' => auth()->id(),
            'guest_name' => auth()->check() ? auth()->user()->name : $this->name,
            'guest_whatsapp' => $this->whatsapp,
            'order_number' => $orderNumber,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $this->notes,
        ]);

        foreach ($this->cartItems as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');
        
        return redirect()->route('order.success', $order->id);
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function render()
    {
        return view('livewire.store.checkout');
    }
}
