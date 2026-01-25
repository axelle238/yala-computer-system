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

    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedSearch() { $this->resetPage(); }

    public function openDetail($id)
    {
        $this->selectedRma = Rma::with(['items.product', 'user', 'order'])->findOrFail($id);
        $this->showDetailModal = true;
        $this->adminNotes = $this->selectedRma->admin_notes; // Load existing notes
    }

    public function updateStatus($newStatus)
    {
        if (!$this->selectedRma) return;

        $this->selectedRma->update([
            'status' => $newStatus,
            'admin_notes' => $this->adminNotes
        ]);

        $this->dispatch('notify', message: 'Status RMA diperbarui.', type: 'success');
        $this->showDetailModal = false;
    }

    public function resolveRma()
    {
        $this->validate([
            'resolutionAction' => 'required|in:repair,replace,refund',
            'adminNotes' => 'required|string|min:5'
        ]);

        DB::transaction(function () {
            // 1. Update RMA
            $this->selectedRma->update([
                'status' => Rma::STATUS_RESOLVED,
                'resolution' => $this->resolutionAction,
                'admin_notes' => $this->adminNotes,
                'resolved_at' => now(),
            ]);

            // 2. Inventory Impact (Only for Replace)
            if ($this->resolutionAction === 'replace') {
                foreach ($this->selectedRma->items as $item) {
                    // Deduct Stock for Replacement Unit
                    $item->product->decrement('stock_quantity', $item->quantity);
                    
                    InventoryTransaction::create([
                        'product_id' => $item->product_id,
                        'user_id' => Auth::id(),
                        'warehouse_id' => 1,
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'unit_price' => 0, // Warranty replacement cost usually absorbed or tracked differently
                        'reference_number' => $this->selectedRma->rma_number,
                        'notes' => 'RMA Replacement Unit Out',
                    ]);
                }
            }
            
            // TODO: Refund Logic would connect to Finance module (Cash Transaction Out)
        });

        $this->dispatch('notify', message: 'RMA diselesaikan (Resolved).', type: 'success');
        $this->showDetailModal = false;
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