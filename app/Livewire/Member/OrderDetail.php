<?php

namespace App\Livewire\Member;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.store')]
#[Title('Detail Pesanan - Yala Computer')]
class OrderDetail extends Component
{
    public Order $pesanan;

    public function mount($id)
    {
        $this->pesanan = Order::with(['item.produk', 'penggunaanVoucher'])->where('user_id', Auth::id())->findOrFail($id);
    }

    public function batalkanPesanan()
    {
        if ($this->pesanan->status === 'pending' && $this->pesanan->payment_status === 'unpaid') {
            $this->pesanan->update(['status' => 'cancelled']);
            $this->dispatch('notify', message: 'Pesanan berhasil dibatalkan.', type: 'success');
        }
    }

    public function bayarSekarang()
    {
        if ($this->pesanan->snap_token) {
            $this->dispatch('trigger-payment', token: $this->pesanan->snap_token, orderId: $this->pesanan->id);
        }
    }

    public function cetakFaktur()
    {
        $this->dispatch('notify', message: 'Fitur cetak faktur akan segera hadir!', type: 'info');
    }

    public function render()
    {
        // Linimasa Pelacakan (Mock berdasarkan status)
        $linimasa = [
            ['status' => 'pending', 'label' => 'Menunggu Pembayaran', 'waktu' => $this->pesanan->created_at, 'selesai' => true],
            ['status' => 'processing', 'label' => 'Diproses Penjual', 'waktu' => $this->pesanan->paid_at ?? null, 'selesai' => in_array($this->pesanan->status, ['processing', 'shipped', 'completed', 'received'])],
            ['status' => 'shipped', 'label' => 'Sedang Dikirim', 'waktu' => null, 'selesai' => in_array($this->pesanan->status, ['shipped', 'completed', 'received'])],
            ['status' => 'completed', 'label' => 'Selesai', 'waktu' => null, 'selesai' => in_array($this->pesanan->status, ['completed', 'received'])],
        ];

        return view('livewire.member.order-detail', [
            'linimasa' => $linimasa,
        ]);
    }
}
