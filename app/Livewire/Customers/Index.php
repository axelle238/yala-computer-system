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
        $totalMembers = Customer::count();
        $newMembersThisMonth = Customer::whereMonth('join_date', Carbon::now()->month)->count();

        // Data List with Lifetime Value (Total Spent)
        $customers = Customer::withSum('orders as total_spent', 'total_amount')
            ->where(function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                    ->orWhere('phone', 'like', '%'.$this->cari.'%');
            })
            ->orderBy('total_spent', 'desc') // Sort by LTV (VIP Customers first)
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
            'totalMembers' => $totalMembers,
            'newMembersThisMonth' => $newMembersThisMonth,
        ]);
    }
}
