<?php

namespace App\Livewire\Expenses;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Biaya Operasional')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';

    public $showForm = false;

    public $expenseId;

    public $description;

    public $amount;

    public $category = 'operational';

    public $date;

    public $payment_method = 'cash';

    public $receipt_image;

    public function create()
    {
        $this->reset(['expenseId', 'description', 'amount', 'category', 'date', 'payment_method', 'receipt_image']);
        $this->date = date('Y-m-d');
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|min:3',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'category' => 'required',
        ]);

        $path = null;
        if ($this->receipt_image) {
            $path = $this->receipt_image->store('receipts', 'public');
        }

        DB::transaction(function () {
            $expense = Expense::create([
                'user_id' => Auth::id(),
                'title' => $this->description,
                'amount' => $this->amount,
                'category' => $this->category,
                'expense_date' => $this->date,
                // 'payment_method' => $this->payment_method, // Tidak ada di schema
                // 'receipt_path' => $path, // Tidak ada di schema
            ]);

            // Jika tunai, potong dari kasir aktif
            if ($this->payment_method == 'cash') {
                $activeRegister = CashRegister::where('user_id', Auth::id())
                    ->where('status', 'open')
                    ->latest()
                    ->first();

                if ($activeRegister) {
                    CashTransaction::create([
                        'cash_register_id' => $activeRegister->id,
                        'transaction_number' => 'EXP-'.$expense->id,
                        'type' => 'out',
                        'category' => 'expense',
                        'amount' => $this->amount,
                        'description' => 'Biaya: '.$this->description,
                        'reference_id' => $expense->id,
                        'reference_type' => Expense::class,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        });

        $this->dispatch('notify', message: 'Pengeluaran tercatat.', type: 'success');
        $this->showForm = false;
    }

    public function render()
    {
        $expenses = Expense::with('user')
            ->where('title', 'like', '%'.$this->search.'%')
            ->latest('expense_date')
            ->paginate(10);

        // Simple Stats
        $monthlyTotal = Expense::whereMonth('expense_date', now()->month)->sum('amount');

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'monthlyTotal' => $monthlyTotal,
        ]);
    }
}
