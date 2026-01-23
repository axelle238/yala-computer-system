<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Order Details')]
class Show extends Component
{
    public Order $order;

    public function mount($id)
    {
        $this->order = Order::with('items.product')->findOrFail($id);
    }

    public function updateStatus($status)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            $this->order->update(['status' => $status]);
            session()->flash('message', "Order status updated to $status");
        }
    }

    public function updatePaymentStatus($status)
    {
        $validStatuses = ['unpaid', 'paid', 'refunded'];
        if (in_array($status, $validStatuses)) {
            $this->order->update(['payment_status' => $status]);
            session()->flash('message', "Payment status updated to $status");
        }
    }

    public function render()
    {
        return view('livewire.orders.show');
    }
}
