<?php

namespace App\Livewire\Employees;

use App\Models\Commission;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Payroll Manager - Yala Computer')]
class PayrollManager extends Component
{
    public $period; // 2026-01

    public function mount()
    {
        $this->period = date('Y-m');
    }

    public function generatePayroll()
    {
        // 1. Get all employees
        $employees = User::whereIn('role', ['technician', 'cashier', 'admin', 'warehouse'])->get();

        $count = 0;

        DB::transaction(function () use ($employees) {
            foreach ($employees as $employee) {
                // Check if already generated
                $exists = Payroll::where('user_id', $employee->id)
                    ->where('period_month', $this->period)
                    ->exists();

                if ($exists) continue;

                // 2. Calculate Commissions (Unpaid ones)
                // Note: In real app, filter by date range of period
                $commissions = Commission::where('user_id', $employee->id)
                    ->where('is_paid', false)
                    ->sum('amount');

                // 3. Basic Salary (Mock logic - should be in user table or contract)
                $basicSalary = $employee->salary ?? 3000000; 

                // 4. Create Payroll Draft
                $payroll = Payroll::create([
                    'user_id' => $employee->id,
                    'period_month' => $this->period,
                    'basic_salary' => $basicSalary,
                    'total_commission' => $commissions,
                    'total_allowance' => 0, // Placeholder
                    'total_deduction' => 0, // Placeholder (e.g. late penalty)
                    'net_salary' => $basicSalary + $commissions,
                    'status' => 'draft'
                ]);

                // 5. Link Commissions to this Payroll
                Commission::where('user_id', $employee->id)
                    ->where('is_paid', false)
                    ->update([
                        'is_paid' => true, 
                        'payroll_id' => $payroll->id
                    ]);

                $count++;
            }
        });

        $this->dispatch('notify', message: "Berhasil generate $count slip gaji (Draft).", type: 'success');
    }

    public function approve($id)
    {
        Payroll::find($id)->update(['status' => 'approved']);
        $this->dispatch('notify', message: 'Payroll approved.', type: 'success');
    }

    public function markPaid($id)
    {
        Payroll::find($id)->update(['status' => 'paid', 'paid_at' => now()]);
        $this->dispatch('notify', message: 'Payroll marked as PAID.', type: 'success');
    }

    public function render()
    {
        $payrolls = Payroll::with('user')
            ->where('period_month', $this->period)
            ->latest()
            ->paginate(10);

        return view('livewire.employees.payroll-manager', [
            'payrolls' => $payrolls
        ]);
    }
}