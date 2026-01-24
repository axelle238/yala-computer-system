<?php

namespace App\Livewire\Employees;

use App\Models\Reimbursement as Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Klaim Reimbursement - Yala Computer')]
class Reimbursement extends Component
{
    use WithPagination, WithFileUploads;

    public $amount;
    public $date;
    public $category = 'Transport';
    public $description;
    public $proof;

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'category' => 'required',
            'description' => 'required',
            'proof' => 'nullable|image|max:2048',
        ]);

        $path = $this->proof ? $this->proof->store('reimbursements', 'public') : null;

        Claim::create([
            'claim_number' => 'CLM-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => Auth::id(),
            'date' => $this->date,
            'category' => $this->category,
            'amount' => $this->amount,
            'description' => $this->description,
            'proof_file' => $path,
            'status' => 'pending',
        ]);

        $this->reset(['amount', 'description', 'proof']);
        $this->dispatch('notify', message: 'Klaim berhasil diajukan.', type: 'success');
    }

    public function approve($id)
    {
        if (Auth::user()->isAdmin() || Auth::user()->isOwner()) {
            Claim::where('id', $id)->update(['status' => 'approved', 'approved_by' => Auth::id()]);
            $this->dispatch('notify', message: 'Klaim disetujui.', type: 'success');
        }
    }

    public function reject($id)
    {
        if (Auth::user()->isAdmin() || Auth::user()->isOwner()) {
            Claim::where('id', $id)->update(['status' => 'rejected', 'approved_by' => Auth::id()]);
            $this->dispatch('notify', message: 'Klaim ditolak.', type: 'success');
        }
    }

    public function render()
    {
        $query = Claim::with('user')->latest();

        if (!Auth::user()->isAdmin() && !Auth::user()->isOwner()) {
            $query->where('user_id', Auth::id());
        }

        return view('livewire.employees.reimbursement', [
            'claims' => $query->paginate(10)
        ]);
    }
}
