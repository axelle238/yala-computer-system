<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Manajemen Pesanan (Kanban) - Admin')]
class Index extends Component
{
    public $cari = '';

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);

        // Validation logic based on current status could go here

        $order->update(['status' => $newStatus]);

        if ($newStatus === 'completed') {
            // Trigger loyalty points awarding logic here if not yet done
        }

        $this->dispatch('notify', message: "Status pesanan {$order->order_number} diubah menjadi ".ucfirst($newStatus), type: 'success');
    }

    public function render()
    {
        $query = Order::with(['user', 'item'])
            ->when($this->cari, function ($query) {
                $query->where('order_number', 'like', '%'.$this->cari.'%')
                    ->orWhere('guest_name', 'like', '%'.$this->cari.'%');
            })
            ->latest();

        // Group orders by status for Kanban columns
        $orders = $query->get();

        $columns = [
            'pending' => $orders->where('status', 'pending'),
            'processing' => $orders->where('status', 'processing'),
            'shipped' => $orders->where('status', 'shipped'),
            'completed' => $orders->where('status', 'completed'),
            'cancelled' => $orders->where('status', 'cancelled'),
        ];

        // Statistik Dashboard Pesanan
        $stats = [
            'today_count' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_amount'),
            'needs_action' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'cancelled_month' => Order::where('status', 'cancelled')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('livewire.orders.index', [
            'columns' => $columns,
            'stats' => $stats,
        ]);
    }
}
