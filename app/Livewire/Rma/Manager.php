<?php

namespace App\Livewire\Rma;

use App\Models\Rma;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen RMA & Garansi')]
class Manager extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';

    // Detail Modal State
    public $selectedRma = null;
    public $showDetailModal = false;
    
    // Action Inputs
    public $adminNotes = '';
    public $resolutionAction = ''; // repair, replace, refund
    public $refundAmount = 0; // New Property

    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }

    public function openDetail($id)
    {
        $this->selectedRma = Rma::with(['items.product', 'user', 'order'])->findOrFail($id);
        $this->showDetailModal = true;
        $this->adminNotes = $this->selectedRma->admin_notes; // Load existing notes
        $this->refundAmount = $this->selectedRma->refund_amount ?? 0;
    }
    
    // ...

    public function resolveRma()
    {
        $this->validate([
            'resolutionAction' => 'required|in:repair,replace,refund',
            'adminNotes' => 'required|string|min:5',
            'refundAmount' => 'required_if:resolutionAction,refund|numeric|min:0',
        ]);

        try {
            DB::transaction(function () {
                // 1. Update RMA
                $this->selectedRma->update([
                    'status' => Rma::STATUS_RESOLVED,
                    'resolution' => $this->resolutionAction, // Pastikan kolom ini ada di migrasi, atau gunakan resolution_type
                    'admin_notes' => $this->adminNotes,
                    'refund_amount' => $this->resolutionAction === 'refund' ? $this->refundAmount : 0,
                    'resolved_at' => now(), // Pastikan kolom ini ada atau gunakan updated_at
                ]);
                
                // ... (Inventory Impact)

                // 3. Refund Logic
                if ($this->resolutionAction === 'refund') {
                     // ... (Logic sebelumnya)
                     // Gunakan $this->refundAmount langsung
                     $amount = $this->refundAmount;
                     
                     // ...
                     
                    CashTransaction::create([
                        'cash_register_id' => $activeRegister->id,
                        'transaction_number' => 'REF-' . time(),
                        'type' => 'out',
                        'category' => 'refund',
                        'amount' => $amount,
                        'description' => 'Refund RMA #' . $this->selectedRma->rma_number,
                        'reference_type' => Rma::class,
                        'reference_id' => $this->selectedRma->id,
                        'created_by' => Auth::id(),
                    ]);
                }
            });

            $this->dispatch('notify', message: 'RMA diselesaikan (Resolved).', type: 'success');
            $this->showDetailModal = false;

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $rmas = Rma::with('user')
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn($q) => $q->where('rma_number', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(10);

        return view('livewire.rma.manager', [
            'rmas' => $rmas
        ]);
    }
}