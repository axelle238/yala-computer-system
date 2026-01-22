<?php

namespace App\Livewire\Components;

use App\Models\Product;
use App\Models\ServiceTicket;
use Livewire\Component;

class Spotlight extends Component
{
    public $search = '';
    public $results = [];
    public $isOpen = false;

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            return;
        }

        $this->results = [];

        // 1. Search Menus
        $menus = [
            ['title' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'home'],
            ['title' => 'Produk & Stok', 'url' => route('products.index'), 'icon' => 'box'],
            ['title' => 'Tambah Produk Baru', 'url' => route('products.create'), 'icon' => 'plus'],
            ['title' => 'Laporan Transaksi', 'url' => route('transactions.index'), 'icon' => 'document-text'],
            ['title' => 'Catat Transaksi Baru', 'url' => route('transactions.create'), 'icon' => 'shopping-cart'],
            ['title' => 'Service Center', 'url' => route('services.index'), 'icon' => 'wrench'],
            ['title' => 'Input Tiket Servis', 'url' => route('services.create'), 'icon' => 'ticket'],
            ['title' => 'Pengaturan Sistem', 'url' => route('settings.index'), 'icon' => 'cog'],
        ];

        foreach ($menus as $menu) {
            if (stripos($menu['title'], $this->search) !== false) {
                $this->results[] = [
                    'title' => $menu['title'],
                    'subtitle' => 'Navigasi Menu',
                    'url' => $menu['url'],
                    'type' => 'menu',
                    'icon' => $menu['icon']
                ];
            }
        }

        // 2. Search Products
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->take(3)->get();

        foreach ($products as $product) {
            $this->results[] = [
                'title' => $product->name,
                'subtitle' => 'Produk - Stok: ' . $product->stock_quantity,
                'url' => route('products.edit', $product->id),
                'type' => 'product',
                'icon' => 'cube'
            ];
        }

        // 3. Search Services
        $tickets = ServiceTicket::where('ticket_number', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->take(3)->get();

        foreach ($tickets as $ticket) {
            $this->results[] = [
                'title' => $ticket->ticket_number . ' - ' . $ticket->customer_name,
                'subtitle' => 'Servis - Status: ' . ucfirst($ticket->status),
                'url' => route('services.edit', $ticket->id),
                'type' => 'service',
                'icon' => 'ticket'
            ];
        }
    }

    public function render()
    {
        return view('livewire.components.spotlight');
    }
}
