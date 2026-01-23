<?php

namespace App\Livewire\Shift;

use App\Models\CashRegister;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Shift Kasir - Yala Computer')]
class Manager extends Component
{
    public $opening_amount = 0;
    public $closing_amount = 0;
    public $note;
    
    public ?CashRegister $activeRegister = null;

    public function mount()
    {
        $this->activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();
    }

    public function openRegister()
    {
        $this->validate([
            'opening_amount' => 'required|numeric|min:0'
        ]);

        CashRegister::create([
            'user_id' => Auth::id(),
            'opened_at' => now(),
            'opening_cash' => $this->opening_amount,
            'status' => 'open'
        ]);

        return redirect()->route('transactions.create');
    }

    public function closeRegister()
    {
        if (!$this->activeRegister) return;

        $this->validate([
            'closing_amount' => 'required|numeric|min:0'
        ]);

        // Hitung Expected Cash
        // Opening + (Cash Sales) - (Cash Refunds/Expenses if any)
        // Untuk sekarang kita hitung dari Order yang payment_method = 'cash' dan register_id ini
        $cashSales = Order::where('cash_register_id', $this->activeRegister->id)
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        $expected = $this->activeRegister->opening_cash + $cashSales;
        $variance = $this->closing_amount - $expected;

        $this->activeRegister->update([
            'closed_at' => now(),
            'closing_cash' => $this->closing_amount,
            'expected_cash' => $expected,
            'variance' => $variance,
            'status' => 'closed',
            'note' => $this->note
        ]);

        return redirect()->route('dashboard')->with('success', 'Shift berhasil ditutup. Laporan Z-Report disimpan.');
    }

    public function render()
    {
        // Hitung statistik real-time untuk shift aktif
        $stats = [];
        if ($this->activeRegister) {
            $sales = Order::where('cash_register_id', $this->activeRegister->id)
                ->selectRaw('payment_method, sum(total_amount) as total, count(*) as count')
                ->groupBy('payment_method')
                ->get();
            
            $stats['total_sales'] = $sales->sum('total');
            $stats['transaction_count'] = $sales->sum('count');
            $stats['cash_in_drawer'] = $this->activeRegister->opening_cash + ($sales->where('payment_method', 'cash')->first()->total ?? 0);
            $stats['breakdown'] = $sales;
        }

        return view('livewire.shift.manager', [
            'stats' => $stats
        ]);
    }
}
