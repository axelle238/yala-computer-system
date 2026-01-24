<?php

namespace App\Livewire\Employees;

use App\Models\Attendance;
use App\Models\Commission;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Payroll Automation System - Yala Computer')]
class PayrollManager extends Component
{
    use WithPagination;

    public $month;
    public $year;
    
    // View States
    public $viewMode = 'list'; // list, generate_preview, detail
    public $processing = false;

    // Generated Preview Data
    public $previewData = [];
    public $selectedPayroll = null;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function startGeneration()
    {
        // Check if payroll already exists for this period
        $exists = Payroll::where('period_month', sprintf('%02d-%d', $this->month, $this->year))->exists();
        
        if ($exists) {
            $this->dispatch('notify', message: 'Payroll untuk periode ini sudah dibuat.', type: 'error');
            return;
        }

        $this->calculatePreview();
        $this->viewMode = 'generate_preview';
    }

    public function calculatePreview()
    {
        $users = User::where('is_active', true)->whereNotNull('base_salary')->get();
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();

        // Get Global Settings
        $mealAllowance = (float) Setting::get('salary_meal_allowance', 25000); // Uang makan harian
        $overtimeRate = (float) Setting::get('salary_overtime_rate', 20000); // Per jam

        $preview = [];

        foreach ($users as $user) {
            // 1. Attendance Stats
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();
            
            $daysPresent = $attendances->where('status', 'present')->count();
            $totalOvertimeHours = $attendances->sum('overtime_hours');

            // 2. Allowances Calculation
            $transportAllowance = 0; // Bisa ambil dari user->transport_allowance kalau ada column
            $mealTotal = $daysPresent * $mealAllowance;
            $totalAllowance = $mealTotal + $transportAllowance;

            // 3. Overtime Calculation
            $overtimePay = $totalOvertimeHours * $overtimeRate;

            // 4. Commissions (Unpaid ones up to end of selected month)
            $commissions = Commission::where('user_id', $user->id)
                ->where('is_paid', false)
                ->where('created_at', '<=', $endOfMonth)
                ->sum('amount');

            // 5. Total
            $gross = $user->base_salary + $totalAllowance + $overtimePay + $commissions;
            $deductions = 0; // Future: Late penalty, Cashbon
            $net = $gross - $deductions;

            $preview[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'base_salary' => $user->base_salary,
                'days_present' => $daysPresent,
                'meal_allowance' => $mealTotal,
                'overtime_hours' => $totalOvertimeHours,
                'overtime_pay' => $overtimePay,
                'commission' => $commissions,
                'deductions' => $deductions,
                'net_salary' => $net,
                'details' => [
                    'meal_rate' => $mealAllowance,
                    'ot_rate' => $overtimeRate,
                    'days' => $daysPresent,
                    'ot_hours' => $totalOvertimeHours
                ]
            ];
        }

        $this->previewData = $preview;
    }

    public function storePayroll()
    {
        $this->processing = true;

        DB::transaction(function () {
            $period = sprintf('%02d-%d', $this->month, $this->year);

            foreach ($this->previewData as $data) {
                // Create Payroll Record
                Payroll::create([
                    'user_id' => $data['user_id'],
                    'period_month' => $period,
                    'pay_date' => now(),
                    'base_salary' => $data['base_salary'],
                    'total_allowance' => $data['meal_allowance'], // Simpifikasi ke total
                    'overtime_pay' => $data['overtime_pay'],
                    'total_commission' => $data['commission'],
                    'deductions' => $data['deductions'],
                    'net_salary' => $data['net_salary'],
                    'status' => 'paid', // Auto paid for simplicity in this flow
                    'details' => $data['details']
                ]);

                // Mark Commissions as Paid
                if ($data['commission'] > 0) {
                    Commission::where('user_id', $data['user_id'])
                        ->where('is_paid', false)
                        ->where('created_at', '<=', Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth())
                        ->update(['is_paid' => true]);
                }
            }
        });

        $this->processing = false;
        $this->viewMode = 'list';
        $this->dispatch('notify', message: 'Payroll berhasil digenerate & disimpan!', type: 'success');
    }

    public function showDetail($id)
    {
        $this->selectedPayroll = Payroll::with('user')->findOrFail($id);
        $this->viewMode = 'detail';
    }

    public function render()
    {
        $payrolls = [];
        if ($this->viewMode === 'list') {
            $payrolls = Payroll::with('user')
                ->latest('period_month')
                ->latest('id')
                ->paginate(10);
        }

        return view('livewire.employees.payroll-manager', [
            'payrolls' => $payrolls,
            'months' => [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ]
        ]);
    }
}
