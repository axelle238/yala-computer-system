<?php

namespace App\Livewire\Rma;

use App\Models\Rma;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('RMA Manager - Yala Computer')]
class Manager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public $showModal = false;
    public $selectedRma;
    public $adminNotes;
    public $updateStatusTo;

    public function updatingSearch() { $this->resetPage(); }

    public function manage($id)
    {
        $this->selectedRma = Rma::with(['items.product', 'user', 'order'])->findOrFail($id);
        $this->adminNotes = $this->selectedRma->admin_notes;
        $this->updateStatusTo = $this->selectedRma->status;
        $this->showModal = true;
    }

    public function saveManagement()
    {
        if (!$this->selectedRma) return;

        $this->selectedRma->update([
            'status' => $this->updateStatusTo,
            'admin_notes' => $this->adminNotes
        ]);

        // Logic for specific statuses (e.g., if approved, maybe notify user)
        if ($this->updateStatusTo === 'approved' && $this->selectedRma->wasChanged('status')) {
            // Notification logic here
        }

        $this->dispatch('notify', message: 'RMA updated successfully.', type: 'success');
        $this->showModal = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $rmas = Rma::with(['user', 'items'])
            ->when($this->search, function($q) {
                $q->where('rma_number', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.rma.manager', [
            'rmas' => $rmas
        ]);
    }
}