<?php

namespace App\Livewire\Finance;

use App\Models\Customer;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Piutang - Yala Computer')]
class Receivables extends Component
{
    public function render()
    {
        // Customers with debt
        $debtors = Customer::where('current_debt', '>', 0)
            ->orderByDesc('current_debt')
            ->get();

        // Overdue Orders (Jatuh Tempo)
        $overdueOrders = Order::where('payment_status', 'unpaid') // Or partial
            ->where('payment_method', 'credit') // Asumsi credit method
            ->where('due_date', '<', now())
            ->with('customer') // If relation exists directly or via user
            ->get();

        return view('livewire.finance.receivables', [
            'debtors' => $debtors,
            'overdueOrders' => $overdueOrders
        ]);
    }
}
