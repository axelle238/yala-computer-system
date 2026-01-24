<?php

namespace App\Livewire\Finance;

use App\Models\CashRegister;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Manajemen Kasir & Shift - Yala Computer')]
class CashRegisterManager extends Component
{
    use WithPagination;

    // View State
    public $viewMode = 'dashboard'; // dashboard, open_modal, close_modal, history_detail
    public $activeRegister = null;

    // Open Register Inputs
    public $openingCash = 0;
    public $openNote = '';

    // Close Register Inputs
    public $closingCash = 0;
    public $closeNote = '';
    public $calculatedStats = [];

    // Detail View
    public $selectedRegisterId = null;

    public function mount()
    {
        $this->checkActiveRegister();
    }

    public function checkActiveRegister()
    {
        $this->activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        // If active, calculate stats immediately for dashboard
        if ($this->activeRegister) {
            $this->calculateCurrentStats();
        }
    }

    public function calculateCurrentStats()
    {
        if (!$this->activeRegister) return;

        $orders = Order::where('cash_register_id', $this->activeRegister->id)
            ->where('status', 'completed')
            ->get();

        $this->calculatedStats = [
            'total_sales' => $orders->sum('total_amount'),
            'cash_sales' => $orders->where('payment_method', 'cash')->sum('total_amount'),
            'transfer_sales' => $orders->where('payment_method', 'transfer')->sum('total_amount'),
            'qris_sales' => $orders->where('payment_method', 'qris')->sum('total_amount'),
            'transaction_count' => $orders->count(),
            'expected_cash_in_drawer' => $this->activeRegister->opening_cash + $orders->where('payment_method', 'cash')->sum('total_amount'),
        ];
    }

    // --- Actions ---

    public function openRegister()
    {
        $this->validate([
            'openingCash' => 'required|numeric|min:0',
        ]);

        CashRegister::create([
            'user_id' => Auth::id(),
            'opened_at' => now(),
            'opening_cash' => $this->openingCash,
            'status' => 'open',
            'note' => $this->openNote,
        ]);

        $this->dispatch('notify', message: 'Shift Kasir Berhasil Dibuka!', type: 'success');
        $this->reset(['openingCash', 'openNote']);
        $this->checkActiveRegister();
        $this->viewMode = 'dashboard';
    }

    public function prepareClose()
    {
        $this->calculateCurrentStats();
        $this->viewMode = 'close_modal';
    }

    public function closeRegister()
    {
        $this->validate([
            'closingCash' => 'required|numeric|min:0',
        ]);

        if (!$this->activeRegister) return;

        $expected = $this->calculatedStats['expected_cash_in_drawer'];
        $variance = $this->closingCash - $expected;

        $this->activeRegister->update([
            'closed_at' => now(),
            'closing_cash' => $this->closingCash,
            'expected_cash' => $expected,
            'variance' => $variance,
            'status' => 'closed',
            'note' => $this->closeNote . ($variance != 0 ? " [Selisih: " . number_format($variance) . "]" : ""),
        ]);

        $this->dispatch('notify', message: 'Shift Kasir Ditutup. Laporan tersimpan.', type: 'success');
        $this->reset(['closingCash', 'closeNote']);
        $this->activeRegister = null;
        $this->viewMode = 'dashboard';
    }

    public function viewDetail($id)
    {
        $this->selectedRegisterId = $id;
        $this->viewMode = 'history_detail';
    }

    public function render()
    {
        // History Data
        $history = CashRegister::with('user')
            ->where('user_id', Auth::id()) // Or remove this if admin wants to see all
            ->latest()
            ->paginate(10);
            
        // Detail Data
        $detailRegister = null;
        $detailOrders = [];
        if ($this->selectedRegisterId) {
            $detailRegister = CashRegister::find($this->selectedRegisterId);
            $detailOrders = Order::where('cash_register_id', $this->selectedRegisterId)->get();
        }

        return view('livewire.finance.cash-register-manager', [
            'history' => $history,
            'detailRegister' => $detailRegister,
            'detailOrders' => $detailOrders
        ]);
    }
}
