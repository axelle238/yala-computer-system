<?php

namespace App\Livewire\Member;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Detail Pesanan - Yala Computer')]
class OrderDetail extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with(['items.product', 'voucherUsages'])->where('user_id', Auth::id())->findOrFail($id);
    }

    public function cancelOrder()
    {
        if ($this->order->status === 'pending' && $this->order->payment_status === 'unpaid') {
            $this->order->update(['status' => 'cancelled']);
            $this->dispatch('notify', message: 'Pesanan dibatalkan.', type: 'success');
        }
    }

    public function payNow()
    {
        if ($this->order->snap_token) {
            $this->dispatch('trigger-payment', token: $this->order->snap_token, orderId: $this->order->id);
        }
    }

    public function printInvoice()
    {
        $this->dispatch('notify', message: 'Fitur cetak invoice akan segera hadir!', type: 'info');
    }

    public function render()
    {
        // Tracking Timeline (Mock based on status)
        $timeline = [
            ['status' => 'pending', 'label' => 'Menunggu Pembayaran', 'time' => $this->order->created_at, 'done' => true],
            ['status' => 'processing', 'label' => 'Diproses Penjual', 'time' => $this->order->paid_at ?? null, 'done' => in_array($this->order->status, ['processing', 'shipped', 'completed', 'received'])],
            ['status' => 'shipped', 'label' => 'Sedang Dikirim', 'time' => null, 'done' => in_array($this->order->status, ['shipped', 'completed', 'received'])],
            ['status' => 'completed', 'label' => 'Selesai', 'time' => null, 'done' => in_array($this->order->status, ['completed', 'received'])],
        ];

        return view('livewire.member.order-detail', [
            'timeline' => $timeline
        ]);
    }
}
