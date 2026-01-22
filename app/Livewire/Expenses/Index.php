<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Biaya Operasional - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    // Form
    public $title, $amount, $expense_date, $category = 'operational', $notes;
    public $isModalOpen = false;

    public function mount()
    {
        $this->expense_date = date('Y-m-d');
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['title', 'amount', 'notes']);
        $this->expense_date = date('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'title' => 'required',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'title' => $this->title,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'category' => $this->category,
            'notes' => $this->notes,
            'user_id' => auth()->id(),
        ]);

        session()->flash('success', 'Pengeluaran berhasil dicatat.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Expense::findOrFail($id)->delete();
        session()->flash('success', 'Data dihapus.');
    }

    public function render()
    {
        $expenses = Expense::latest()->paginate(10);
        return view('livewire.expenses.index', ['expenses' => $expenses]);
    }
}
