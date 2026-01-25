<?php

namespace App\Livewire\Components;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class Spotlight extends Component
{
    public $search = '';

    public $results = [];

    public $isOpen = false;

    // Listen to keyboard shortcut from global JS
    protected $listeners = ['open-spotlight' => 'open'];

    public function open()
    {
        $this->isOpen = true;
        $this->dispatch('focus-spotlight');
    }

    public function close()
    {
        $this->isOpen = false;
        $this->search = '';
        $this->results = [];
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];

            return;
        }

        $this->results = [
            'Menus' => $this->searchMenus(),
            'Products' => $this->searchProducts(),
            'Customers' => $this->searchCustomers(),
            'Orders' => $this->searchOrders(),
        ];
    }

    private function searchMenus()
    {
        $menus = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'home'],
            ['label' => 'Kasir (POS)', 'route' => 'sales.pos', 'icon' => 'shopping-cart'],
            ['label' => 'Daftar Produk', 'route' => 'products.index', 'icon' => 'tag'],
            ['label' => 'Stok Opname', 'route' => 'warehouses.stock-opname', 'icon' => 'clipboard-check'],
            ['label' => 'Purchase Orders', 'route' => 'purchase-orders.index', 'icon' => 'truck'],
            ['label' => 'Service Workbench', 'route' => 'services.index', 'icon' => 'wrench'],
            ['label' => 'Laporan Keuangan', 'route' => 'reports.finance', 'icon' => 'chart-pie'],
            ['label' => 'Manajemen Pegawai', 'route' => 'employees.index', 'icon' => 'users'],
            ['label' => 'Pengaturan Sistem', 'route' => 'settings.index', 'icon' => 'cog'],
        ];

        return collect($menus)->filter(function ($menu) {
            return stripos($menu['label'], $this->search) !== false;
        })->take(3)->all();
    }

    private function searchProducts()
    {
        return Product::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->take(3)
            ->get()
            ->map(fn ($p) => [
                'label' => $p->name,
                'sub' => 'Stok: '.$p->stock_quantity,
                'route' => route('products.edit', $p->id), // Asumsi ada route edit
                'icon' => 'box',
            ]);
    }

    private function searchCustomers()
    {
        return Customer::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('phone', 'like', '%'.$this->search.'%')
            ->take(3)
            ->get()
            ->map(fn ($c) => [
                'label' => $c->name,
                'sub' => $c->phone,
                'route' => route('customers.edit', $c->id),
                'icon' => 'user',
            ]);
    }

    private function searchOrders()
    {
        return Order::where('order_number', 'like', '%'.$this->search.'%')
            ->take(3)
            ->get()
            ->map(fn ($o) => [
                'label' => 'Order #'.$o->order_number,
                'sub' => $o->status.' - '.$o->guest_name,
                'route' => route('orders.show', $o->id), // Asumsi route show order admin
                'icon' => 'receipt-tax',
            ]);
    }

    public function render()
    {
        return view('livewire.components.spotlight');
    }
}
