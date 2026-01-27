<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('CRM Dashboard - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public function render()
    {
        // Statistics
        $stats = [
            'total' => Customer::count(),
            'new_this_month' => Customer::whereMonth('created_at', Carbon::now()->month)->count(),
            'active_members' => Customer::whereHas('orders')->count(), // Pernah belanja
            'top_tier' => Customer::where('loyalty_points', '>', 1000)->count(), // Contoh logika tier
        ];

        // Data List with Lifetime Value (Total Spent)
        $customers = Customer::withSum('orders as total_spent', 'total_amount')
            ->where(function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                    ->orWhere('phone', 'like', '%'.$this->cari.'%')
                    ->orWhere('email', 'like', '%'.$this->cari.'%');
            })
            ->orderBy('total_spent', 'desc') // Sort by LTV (VIP Customers first)
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
            'stats' => $stats,
        ]);
    }
}
