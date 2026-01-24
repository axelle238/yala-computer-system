<?php

namespace App\Livewire\Front;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Lacak Pesanan - Yala Computer')]
class TrackOrder extends Component
{
    public $order_number = '';
    public $contact = ''; // Email or Phone
    public $order = null;

    public function track()
    {
        $this->validate([
            'order_number' => 'required|string',
            'contact' => 'required|string',
        ]);

        $this->order = Order::with(['items.product'])
            ->where('order_number', $this->order_number)
            ->where(function($q) {
                $q->where('guest_whatsapp', 'like', '%' . $this->contact . '%')
                  ->orWhereHas('user', function($u) {
                      $u->where('email', $this->contact);
                  });
            })
            ->first();

        if (!$this->order) {
            $this->addError('order_number', 'Pesanan tidak ditemukan. Periksa nomor order dan kontak Anda.');
        }
    }

    public function render()
    {
        $timeline = [];
        if ($this->order) {
            $timeline = [
                ['status' => 'pending', 'label' => 'Pesanan Dibuat', 'time' => $this->order->created_at, 'done' => true],
                ['status' => 'processing', 'label' => 'Diproses', 'time' => $this->order->paid_at, 'done' => in_array($this->order->status, ['processing', 'shipped', 'completed', 'received'])],
                ['status' => 'shipped', 'label' => 'Dalam Pengiriman', 'time' => null, 'done' => in_array($this->order->status, ['shipped', 'completed', 'received'])],
                ['status' => 'completed', 'label' => 'Selesai', 'time' => null, 'done' => in_array($this->order->status, ['completed', 'received'])],
            ];
        }

        return view('livewire.front.track-order', [
            'timeline' => $timeline
        ]);
    }
}
