<?php

namespace App\Livewire\Employees;

use App\Models\InventoryTransaction;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Payroll & Kinerja - Yala Computer')]
class Payroll extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function render()
    {
        $employees = User::where('role', 'employee')->get()->map(function($user) {
            // Hitung Total Penjualan Bulan Ini
            $salesTotal = InventoryTransaction::where('user_id', $user->id)
                ->where('type', 'out')
                ->whereMonth('created_at', $this->month)
                ->whereYear('created_at', $this->year)
                ->with('product')
                ->get()
                ->sum(function($trx) {
                    return $trx->quantity * $trx->product->sell_price;
                });

            // Hitung Komisi (Contoh: 1% dari omzet pribadi)
            $commission = $salesTotal * 0.01;
            
            // Total Gaji
            $totalSalary = $user->base_salary + $commission;

            return [
                'name' => $user->name,
                'email' => $user->email,
                'join_date' => $user->join_date ? $user->join_date->format('d M Y') : '-',
                'base_salary' => $user->base_salary,
                'sales_total' => $salesTotal,
                'commission' => $commission,
                'total_salary' => $totalSalary,
            ];
        });

        return view('livewire.employees.payroll', [
            'employees' => $employees
        ]);
    }
}
