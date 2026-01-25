<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Order Placed')]
class OrderSuccess extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);

        if (auth()->check() && $this->order->user_id && $this->order->user_id !== auth()->id()) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.store.order-success');
    }

    public function sendToWhatsapp()
    {
        $message = "Halo Yala Computer, saya baru saja melakukan order.\n";
        $message .= "No Order: *{$this->order->order_number}*\n";
        $message .= "Nama: {$this->order->guest_name}\n";
        $message .= 'Total: Rp '.number_format($this->order->total_amount, 0, ',', '.')."\n\n";
        $message .= 'Mohon info pembayaran dan pengiriman. Terima kasih.';

        $waNumber = Setting::get('whatsapp_number', '6281234567890'); // Fallback handled in helper usually, but explicit here
        if (! $waNumber) {
            $waNumber = '6281234567890';
        }

        $encodedMessage = urlencode($message);

        return redirect()->away("https://wa.me/{$waNumber}?text={$encodedMessage}");
    }
}
