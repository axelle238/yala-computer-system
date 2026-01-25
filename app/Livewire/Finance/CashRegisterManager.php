<?php

namespace App\Livewire\Finance;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Kasir & Keuangan')]
class CashRegisterManager extends Component
{
    use WithPagination;

    // View State
    public $activeRegister = null;

    public $activeAction = null; // null, 'open', 'close', 'transaction'

    // Open Register Inputs
    public $openingCash = 0;

    public $openNote = '';

    // Close Register Inputs
    public $closingCash = 0;

    public $closeNote = '';

    // Manual Transaction Inputs
    public $trxType = 'out'; // in / out

    public $trxCategory = 'expense'; // expense, sales, service, etc

    public $trxAmount = 0;

    public $trxDescription = '';

    public function mount()
    {
        $this->checkActiveRegister();
    }

    public function setAction($action)
    {
        $this->activeAction = $action;
        $this->resetValidation();
    }

    public function checkActiveRegister()
    {
        $this->activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();
    }

    // --- Computed Properties for Dashboard ---

    public function getCurrentBalanceProperty()
    {
        if (! $this->activeRegister) {
            return 0;
        }

        return $this->activeRegister->system_balance;
    }

    public function getTodayTransactionsProperty()
    {
        if (! $this->activeRegister) {
            return collect();
        }

        return $this->activeRegister->transactions()->latest()->get();
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

        session()->flash('success', 'Shift Kasir Berhasil Dibuka!');
        $this->reset(['openingCash', 'openNote', 'activeAction']);
        $this->checkActiveRegister();
    }

    public function closeRegister()
    {
        $this->validate([
            'closingCash' => 'required|numeric|min:0',
        ]);

        if (! $this->activeRegister) {
            return;
        }

        $expected = $this->currentBalance;
        $variance = $this->closingCash - $expected;

        $this->activeRegister->update([
            'closed_at' => now(),
            'closing_cash' => $this->closingCash,
            'expected_cash' => $expected,
            'variance' => $variance,
            'status' => 'closed',
            'note' => $this->closeNote.($variance != 0 ? ' [Selisih: '.number_format($variance, 0, ',', '.').']' : ''),
        ]);

        session()->flash('success', 'Shift Kasir Ditutup. Laporan tersimpan.');
        $this->reset(['closingCash', 'closeNote', 'activeAction']);
        $this->activeRegister = null;
    }

    public function saveTransaction()
    {
        if (! $this->activeRegister) {
            return;
        }

        $this->validate([
            'trxAmount' => 'required|numeric|min:1',
            'trxDescription' => 'required|string|min:3',
            'trxCategory' => 'required',
        ]);

        // Cek saldo cukup jika pengeluaran
        if ($this->trxType == 'out' && $this->trxAmount > $this->currentBalance) {
            $this->addError('trxAmount', 'Saldo kas tidak mencukupi!');

            return;
        }

        CashTransaction::create([
            'cash_register_id' => $this->activeRegister->id,
            'transaction_number' => 'TRX-'.date('ymd').'-'.rand(1000, 9999),
            'type' => $this->trxType,
            'category' => $this->trxCategory,
            'amount' => $this->trxAmount,
            'description' => $this->trxDescription,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Transaksi berhasil dicatat.');
        $this->reset(['trxAmount', 'trxDescription', 'activeAction']);
        $this->checkActiveRegister(); // Refresh
    }

    public function render()
    {
        $history = CashRegister::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('livewire.finance.cash-register-manager', [
            'history' => $history,
        ]);
    }
}
