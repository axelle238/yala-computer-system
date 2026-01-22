<?php

namespace App\Livewire\Components;

use App\Models\Product;
use App\Models\ServiceTicket;
use Livewire\Component;

class Spotlight extends Component
{
    public $search = '';
    public $isOpen = false;

    public function updatedSearch()
    {
        // No operation needed, render updates results
    }

    public function getResultsProperty()
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        $results = collect();

        // 1. Menu Navigation
        $menus = [
            ['title' => 'Dashboard', 'url' => route('dashboard'), 'subtitle' => 'Go to Dashboard', 'type' => 'menu'],
            ['title' => 'Produk', 'url' => route('products.index'), 'subtitle' => 'Manage Inventory', 'type' => 'menu'],
            ['title' => 'Transaksi', 'url' => route('transactions.index'), 'subtitle' => 'View Transactions', 'type' => 'menu'],
            ['title' => 'Service Center', 'url' => route('services.index'), 'subtitle' => 'Manage Services', 'type' => 'menu'],
            ['title' => 'Pegawai', 'url' => route('employees.index'), 'subtitle' => 'Manage Employees', 'type' => 'menu'],
        ];

        foreach ($menus as $menu) {
            if (stripos($menu['title'], $this->search) !== false) {
                $results->push($menu);
            }
        }

        // 2. Search Products
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->take(5)
            ->get();

        foreach ($products as $product) {
            $results->push([
                'title' => $product->name,
                'url' => route('products.edit', $product->id), // Assuming edit route exists
                'subtitle' => 'Product • ' . $product->sku,
                'type' => 'product'
            ]);
        }

        // 3. Search Service Tickets
        $tickets = ServiceTicket::where('ticket_number', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->take(3)
            ->get();

        foreach ($tickets as $ticket) {
            $results->push([
                'title' => $ticket->ticket_number . ' - ' . $ticket->customer_name,
                'url' => route('services.edit', $ticket->id),
                'subtitle' => 'Service • ' . $ticket->status,
                'type' => 'service'
            ]);
        }

        return $results->all();
    }

    public function render()
    {
        return view('livewire.components.spotlight', [
            'results' => $this->results
        ]);
    }
}