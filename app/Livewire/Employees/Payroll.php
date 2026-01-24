<?php

namespace App\Livewire\Employees;

use App\Models\Attendance;
use App\Models\Commission;
use App\Models\Payroll as PayrollModel;
use App\Models\User;
use Carbon\Carbon;
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

    // Konfigurasi Potongan (Bisa dipindah ke Settings nanti)
    const DEDUCTION_LATE = 50000; // 50rb per terlambat
    const DEDUCTION_ABSENT = 150000; // 150rb per alpha

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
            $users = User::where('role', '!=', 'customer')->get(); 
            
            // Tentukan rentang tanggal absensi (biasanya tgl 26 bulan lalu s/d 25 bulan ini, atau full bulan)
            // Disini kita pakai Full Bulan kalender untuk simplifikasi reporting
            $startDate = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

            foreach ($users as $user) {
                // 1. Hitung Komisi
                $pendingCommissions = Commission::where('user_id', $user->id)
                    ->where('is_paid', false)
                    ->get();
                $totalCommission = $pendingCommissions->sum('amount');
                
                // 2. Data Gaji Pokok
                $baseSalary = $user->base_salary ?? 0;

                // 3. Hitung Absensi & Potongan
                $lateCount = Attendance::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'late')
                    ->count();
                
                // Hitung Alpha (Hari kerja tanpa absen). 
                // Simplifikasi: Kita hitung dari status 'absent' yang tercatat secara manual atau otomatis oleh sistem cron (jika ada).
                // Untuk sekarang kita ambil record yg statusnya 'absent'.
                $absentCount = Attendance::where('user_id', $user->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'absent')
                    ->count();

                $deductionLateAmount = $lateCount * self::DEDUCTION_LATE;
                $deductionAbsentAmount = $absentCount * self::DEDUCTION_ABSENT;
                $totalDeductions = $deductionLateAmount + $deductionAbsentAmount;

                // Detail JSON untuk slip gaji
                $details = [
                    'attendance' => [
                        'late_days' => $lateCount,
                        'absent_days' => $absentCount,
                        'late_penalty' => $deductionLateAmount,
                        'absent_penalty' => $deductionAbsentAmount
                    ],
                    'commission_ids' => $pendingCommissions->pluck('id')->toArray()
                ];

                // Skip jika tidak ada aktivitas finansial sama sekali
                if ($baseSalary == 0 && $totalCommission == 0 && $totalDeductions == 0) continue;

                $netSalary = $baseSalary + $totalCommission - $totalDeductions;

                // Create Payroll Record
                PayrollModel::create([
                    'user_id' => $user->id,
                    'period_month' => $period,
                    'pay_date' => now(),
                    'base_salary' => $baseSalary,
                    'total_commission' => $totalCommission,
                    'deductions' => $totalDeductions,
                    'net_salary' => max(0, $netSalary), // Tidak boleh minus
                    'status' => 'draft',
                    // 'details' => json_encode($details) // Jika ada kolom ini. Jika tidak, simpan di notes atau abaikan dulu.
                ]);

                // Mark commissions as paid
                if ($pendingCommissions->isNotEmpty()) {
                    Commission::whereIn('id', $pendingCommissions->pluck('id'))->update(['is_paid' => true]);
                }
            }
        });

        $this->dispatch('notify', message: 'Payroll berhasil digenerate dengan perhitungan absensi!', type: 'success');
    }

    public function markAsPaid($id)
    {
        PayrollModel::where('id', $id)->update(['status' => 'paid']);
        $this->dispatch('notify', message: 'Status diubah menjadi Paid.', type: 'success');
    }

    public function deletePayroll($id)
    {
        // Fitur rollback payroll (Hanya jika masih draft)
        $payroll = PayrollModel::findOrFail($id);
        if ($payroll->status !== 'draft') {
            $this->dispatch('notify', message: 'Hanya payroll Draft yang bisa dihapus.', type: 'error');
            return;
        }

        // Kembalikan status komisi jadi unpaid (Perlu logika advance, disini kita skip untuk safety)
        // Idealnya kita simpan commission_ids di tabel payroll agar bisa direvert.
        
        $payroll->delete();
        $this->dispatch('notify', message: 'Data payroll dihapus.', type: 'success');
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