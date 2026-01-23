<?php

namespace App\Livewire\Employees;

use App\Models\Commission;
use App\Models\Payroll as PayrollModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Payroll System - Yala Computer')]
class Payroll extends Component
{
    public $month;
    public $year;

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function generatePayroll()
    {
        $period = sprintf('%02d-%d', $this->month, $this->year);

        // Cek apakah sudah pernah generate bulan ini
        $exists = PayrollModel::where('period_month', $period)->exists();
        if ($exists) {
            $this->dispatch('notify', message: 'Payroll periode ini sudah dibuat.', type: 'error');
            return;
        }

        DB::transaction(function () use ($period) {
            $users = User::all(); // Semua user, nanti bisa filter role

            foreach ($users as $user) {
                // Ambil komisi yang belum dibayar
                // Opsional: Filter by month. Tapi biasanya komisi bulan lalu dibayar bulan ini.
                // Disini kita ambil SEMUA komisi pending user ini sampai saat ini.
                $pendingCommissions = Commission::where('user_id', $user->id)
                    ->where('is_paid', false)
                    ->get();
                
                $totalCommission = $pendingCommissions->sum('amount');
                $baseSalary = $user->base_salary ?? 0; // Pastikan kolom ini ada di user (sudah di migrasi)

                // Skip jika tidak ada gaji dan tidak ada komisi (user pasif)
                if ($baseSalary == 0 && $totalCommission == 0) continue;

                // Create Payroll Record
                $payroll = PayrollModel::create([
                    'user_id' => $user->id,
                    'period_month' => $period,
                    'pay_date' => now(),
                    'base_salary' => $baseSalary,
                    'total_commission' => $totalCommission,
                    'deductions' => 0, // Logic potongan bisa dikembangkan nanti
                    'net_salary' => $baseSalary + $totalCommission,
                    'status' => 'draft'
                ]);

                // Mark commissions as paid & linked to payroll (if we had relation, but for now simple bool)
                Commission::whereIn('id', $pendingCommissions->pluck('id'))->update(['is_paid' => true]);
            }
        });

        $this->dispatch('notify', message: 'Payroll berhasil digenerate!', type: 'success');
    }

    public function markAsPaid($id)
    {
        PayrollModel::where('id', $id)->update(['status' => 'paid']);
        $this->dispatch('notify', message: 'Status diubah menjadi Paid.', type: 'success');
    }

    public function render()
    {
        $period = sprintf('%02d-%d', $this->month, $this->year);
        
        $payrolls = PayrollModel::with('user')
            ->where('period_month', $period)
            ->get();

        return view('livewire.employees.payroll', [
            'payrolls' => $payrolls,
            'hasGenerated' => $payrolls->isNotEmpty()
        ]);
    }
}