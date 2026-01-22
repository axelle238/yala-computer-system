<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('CRM Dashboard - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Statistics
        $totalMembers = Customer::count();
        $newMembersThisMonth = Customer::whereMonth('join_date', Carbon::now()->month)->count();
        
        // Data List
        $customers = Customer::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orderBy('points', 'desc') // Sort by loyalty points
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
            'totalMembers' => $totalMembers,
            'newMembersThisMonth' => $newMembersThisMonth,
        ]);
    }
}
