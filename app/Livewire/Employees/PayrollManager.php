<?php

namespace App\Livewire\Employees;

use App\Models\Attendance;
use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\EmployeeDetail;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\ServiceTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Manajemen Penggajian')]
class PayrollManager extends Component
{
    use WithPagination;

    // View State
    public $showGenerateModal = false;
    public $showDetailModal = false;
    
    // Generate Input
    public $selectedUserId;
    public $selectedMonth; // Format: Y-m
    
    // Preview Data
    public $previewData = null; // Menyimpan hasil hitungan sebelum disimpan

    // Detail Data
    public $selectedPayroll;

    public function mount()
    {
        $this->selectedMonth = date('Y-m');
    }

    public function openGenerateModal()
    {
        $this->reset(['selectedUserId', 'previewData']);
        $this->showGenerateModal = true;
    }

    public function calculatePreview()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedMonth' => 'required',
        ]);

        $employee = EmployeeDetail::where('user_id', $this->selectedUserId)->first();
        if (!$employee) {
            $this->addError('selectedUserId', 'Data detail pegawai belum diatur di HR.');
            return;
        }

        $start = Carbon::parse($this->selectedMonth)->startOfMonth();
        $end = Carbon::parse($this->selectedMonth)->endOfMonth();

        // 1. Gaji Pokok
        $baseSalary = $employee->base_salary;

        // 2. Tunjangan Kehadiran
        $attendanceCount = Attendance::where('user_id', $this->selectedUserId)
            ->whereBetween('date', [$start, $end])
            ->whereIn('status', ['present', 'late']) // Late tetap dianggap hadir (bisa kena potong beda lagi)
            ->count();
        $totalAllowance = $attendanceCount * $employee->allowance_daily;

        // 3. Komisi Servis (Kompleks)
        // Ambil tiket yang SELESAI bulan ini oleh teknisi ini
        $tickets = ServiceTicket::where('technician_id', $this->selectedUserId)
            ->where('status', 'picked_up') // Hanya yang sudah dibayar & diambil
            ->whereBetween('updated_at', [$start, $end])
            ->with('parts')
            ->get();

        $commissionAmount = 0;
        $commissionDetails = [];

        if ($employee->commission_percentage > 0) {
            foreach ($tickets as $ticket) {
                // Hitung Profit: Harga Jual - Modal (HPP tidak selalu ada di ticket part, kita asumsi margin kasar atau ambil dari product)
                // Untuk simplifikasi dan performa: Kita hitung dari Total Tagihan Tiket * Persentase Komisi
                // (Idealnya: Revenue - COGS, tapi butuh query berat ke InventoryTransaction/Product Buy Price)
                
                $revenue = $ticket->final_cost; 
                $comm = $revenue * ($employee->commission_percentage / 100);
                
                $commissionAmount += $comm;
                $commissionDetails[] = "Tiket #{$ticket->ticket_number} (Rp " . number_format($revenue) . ")";
            }
        }

        // 4. Potongan Keterlambatan (Opsional)
        $lateMinutes = Attendance::where('user_id', $this->selectedUserId)
            ->whereBetween('date', [$start, $end])
            ->sum('late_minutes');
        
        $lateDeduction = floor($lateMinutes / 10) * 5000; // Rp 5.000 per 10 menit telat

        $netSalary = $baseSalary + $totalAllowance + $commissionAmount - $lateDeduction;

        $this->previewData = [
            'user_id' => $this->selectedUserId,
            'user_name' => $employee->user->name,
            'job_title' => $employee->job_title,
            'period' => $start->translatedFormat('F Y'),
            'base_salary' => $baseSalary,
            'attendance_count' => $attendanceCount,
            'allowance_daily' => $employee->allowance_daily,
            'total_allowance' => $totalAllowance,
            'commission_count' => count($tickets),
            'commission_percentage' => $employee->commission_percentage,
            'total_commission' => $commissionAmount,
            'late_minutes' => $lateMinutes,
            'total_deduction' => $lateDeduction,
            'net_salary' => $netSalary,
        ];
    }

    public function storePayroll()
    {
        if (!$this->previewData) return;

        DB::transaction(function () {
            // 1. Create Header
            $payroll = Payroll::create([
                'user_id' => $this->previewData['user_id'],
                'payroll_number' => 'PAY/' . date('ym') . '/' . $this->previewData['user_id'] . '/' . rand(100,999),
                'period_start' => Carbon::parse($this->selectedMonth)->startOfMonth(),
                'period_end' => Carbon::parse($this->selectedMonth)->endOfMonth(),
                'total_income' => $this->previewData['base_salary'] + $this->previewData['total_allowance'] + $this->previewData['total_commission'],
                'total_deduction' => $this->previewData['total_deduction'],
                'net_salary' => $this->previewData['net_salary'],
                'status' => 'draft', // Harus diapprove dulu untuk bayar
                'details' => $this->previewData, // Simpan snapshot data hitungan
            ]);

            // 2. Create Items (Opsional jika sudah simpan di JSON, tapi bagus untuk reporting)
            // Gaji Pokok
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'title' => 'Gaji Pokok',
                'type' => 'income',
                'amount' => $this->previewData['base_salary'],
            ]);
            // Tunjangan
            PayrollItem::create([
                'payroll_id' => $payroll->id,
                'title' => "Tunjangan Kehadiran ({$this->previewData['attendance_count']} hari)",
                'type' => 'income',
                'amount' => $this->previewData['total_allowance'],
            ]);
            // Komisi
            if ($this->previewData['total_commission'] > 0) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'title' => "Komisi Servis ({$this->previewData['commission_count']} tiket)",
                    'type' => 'income',
                    'amount' => $this->previewData['total_commission'],
                ]);
            }
            // Potongan
            if ($this->previewData['total_deduction'] > 0) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'title' => "Potongan Keterlambatan ({$this->previewData['late_minutes']} menit)",
                    'type' => 'deduction',
                    'amount' => $this->previewData['total_deduction'],
                ]);
            }
        });

        session()->flash('success', 'Slip Gaji berhasil dibuat (Status: Draft).');
        $this->showGenerateModal = false;
        $this->previewData = null;
    }

    public function viewDetail($id)
    {
        $this->selectedPayroll = Payroll::with(['user', 'user.employeeDetail'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function approveAndPay()
    {
        if (!$this->selectedPayroll) return;
        if ($this->selectedPayroll->status !== 'draft') return;

        // Cek Kasir Aktif
        $activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();
        
        if (!$activeRegister) {
            $this->dispatch('notify', message: 'Gagal! Anda harus Buka Kasir dulu untuk mengeluarkan dana gaji.', type: 'error');
            return;
        }

        // Cek Saldo
        if ($activeRegister->system_balance < $this->selectedPayroll->net_salary) {
            $this->dispatch('notify', message: 'Saldo Kasir tidak cukup untuk membayar gaji ini.', type: 'error');
            return;
        }

        DB::transaction(function () use ($activeRegister) {
            // 1. Update Payroll Status
            $this->selectedPayroll->update([
                'status' => 'paid',
                'payment_date' => now(),
                'approved_by' => Auth::id(),
            ]);

            // 2. Create Cash Transaction (Expense)
            CashTransaction::create([
                'cash_register_id' => $activeRegister->id,
                'transaction_number' => 'EXP-PAY-' . $this->selectedPayroll->id,
                'type' => 'out',
                'category' => 'expense', // Beban Gaji
                'amount' => $this->selectedPayroll->net_salary,
                'description' => "Pembayaran Gaji Periode " . Carbon::parse($this->selectedPayroll->period_start)->format('M Y') . " - " . $this->selectedPayroll->user->name,
                'reference_id' => $this->selectedPayroll->id,
                'reference_type' => Payroll::class,
                'created_by' => Auth::id(),
            ]);
        });

        session()->flash('success', 'Gaji berhasil dibayarkan dan dicatat di Kasir.');
        $this->showDetailModal = false;
    }

    public function render()
    {
        $payrolls = Payroll::with('user')
            ->orderBy('period_start', 'desc')
            ->paginate(10);
            
        $employees = User::whereHas('employeeDetail')->get();

        return view('livewire.employees.payroll-manager', [
            'payrolls' => $payrolls,
            'employees' => $employees
        ]);
    }
}
