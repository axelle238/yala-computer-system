<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rma;
use App\Models\RmaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Ajukan Klaim Garansi - Yala Computer')]
class RmaRequest extends Component
{
    public $step = 1;
    public $selectedOrderId = null;
    public $selectedItems = []; // [item_id => ['selected' => bool, 'qty' => 1, 'reason' => '']]

    public function selectOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->step = 2;
    }

    public function submitRma()
    {
        $itemsToReturn = collect($this->selectedItems)->filter(fn($i) => $i['selected'] ?? false);

        if ($itemsToReturn->isEmpty()) {
            $this->addError('items', 'Pilih minimal satu barang.');
            return;
        }

        $rma = Rma::create([
            'rma_number' => 'RMA-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => Auth::id(),
            'order_id' => $this->selectedOrderId,
            'status' => 'pending',
            'reason' => 'Pengajuan via Member Portal',
        ]);

        foreach ($itemsToReturn as $itemId => $data) {
            $orderItem = OrderItem::find($itemId);
            RmaItem::create([
                'rma_id' => $rma->id,
                'product_id' => $orderItem->product_id,
                'quantity' => $data['qty'] ?? 1,
                'serial_number' => $orderItem->serial_numbers, // Copy from order, user might need to specify if multiple
                'problem_description' => $data['reason'] ?? 'Rusak',
            ]);
        }

        session()->flash('success', 'Pengajuan RMA berhasil dikirim. Kami akan segera menghubungi Anda.');
        return redirect()->route('member.dashboard');
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->get();

        $selectedOrderItems = [];
        if ($this->selectedOrderId) {
            $selectedOrderItems = OrderItem::with('product')->where('order_id', $this->selectedOrderId)->get();
        }

        return view('livewire.member.rma-request', [
            'orders' => $orders,
            'orderItems' => $selectedOrderItems
        ]);
    }
}
