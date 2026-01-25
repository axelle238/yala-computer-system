<?php

namespace App\Livewire\Components;

use App\Models\Product;
use App\Models\Order;
use App\Models\ServiceTicket;
use Livewire\Component;

class AdminNotification extends Component
{
    public $isOpen = false;
    public $notifications = [];
    public $hasUnread = false;

    public function mount()
    {
        $this->fetchNotifications();
    }

    public function fetchNotifications()
    {
        // 1. Low Stock Alerts
        $lowStocks = Product::where('stock_quantity', '<=', 5)->where('is_active', true)->take(3)->get();
        foreach($lowStocks as $p) {
            $this->notifications[] = [
                'id' => 'stock-'.$p->id,
                'type' => 'warning',
                'title' => 'Stok Menipis',
                'message' => "Produk {$p->name} tersisa {$p->stock_quantity}.",
                'time' => now()->subMinutes(rand(1, 60)),
                'route' => route('products.edit', $p->id)
            ];
        }

        // 2. New Orders (Pending)
        $newOrders = Order::where('status', 'pending')->take(3)->get();
        foreach($newOrders as $o) {
            $this->notifications[] = [
                'id' => 'order-'.$o->id,
                'type' => 'success',
                'title' => 'Order Baru Masuk',
                'message' => "#{$o->order_number} oleh {$o->guest_name} senilai " . number_format($o->total_amount),
                'time' => $o->created_at,
                'route' => route('orders.show', $o->id)
            ];
        }

        // 3. Service Ticket Updates (Simple Logic: Processing)
        $services = ServiceTicket::where('status', 'processing')->take(2)->get();
        foreach($services as $s) {
            $this->notifications[] = [
                'id' => 'service-'.$s->id,
                'type' => 'info',
                'title' => 'Servis Sedang Dikerjakan',
                'message' => "Tiket #{$s->ticket_number} ({$s->device_name})",
                'time' => $s->updated_at,
                'route' => route('services.workbench', $s->id)
            ];
        }

        // Sort by time
        usort($this->notifications, fn($a, $b) => $b['time'] <=> $a['time']);
        
        $this->hasUnread = count($this->notifications) > 0;
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function markAsRead()
    {
        $this->notifications = [];
        $this->hasUnread = false;
        $this->isOpen = false;
        $this->dispatch('notify', message: 'Semua notifikasi ditandai sudah dibaca.', type: 'success');
    }

    public function render()
    {
        return view('livewire.components.admin-notification');
    }
}