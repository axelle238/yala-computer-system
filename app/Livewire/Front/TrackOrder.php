<?php

namespace App\Livewire\Front;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Lacak Pesanan - Yala Computer')]
class TrackOrder extends Component
{
    public $orderNumber = '';
    public $searchPerformed = false;

    public $order = null;

    public function track()
    {
        $this->validate([
            'orderNumber' => 'required|string',
        ]);

        $this->searchPerformed = true;

        $this->order = Order::with(['items.product'])
            ->where('order_number', $this->orderNumber)
            ->first();

        if (! $this->order) {
            $this->addError('orderNumber', 'Pesanan tidak ditemukan. Periksa nomor order Anda.');
        }
    }

    public function render()
    {
        if ($this->order) {
            $this->order->refresh();
        }

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
            'timeline' => $timeline,
        ]);
    }
}
