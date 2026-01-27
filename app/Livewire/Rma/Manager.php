<?php

namespace App\Livewire\Rma;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Rma;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen RMA & Garansi')]
class Manager extends Component
{
    use WithPagination;

    public $statusFilter = '';

    public $search = '';

    // View State
    public $activeAction = null; // null, 'detail'

    public $selectedRma = null;

    // Action Inputs
    public $adminNotes = '';

    public $resolutionAction = ''; // repair, replace, refund

    public $refundAmount = 0; // New Property

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openDetailPanel($id)
    {
        $this->selectedRma = Rma::with(['item.product', 'user', 'order'])->findOrFail($id);
        $this->activeAction = 'detail';
        $this->adminNotes = $this->selectedRma->admin_notes; // Load existing notes
        $this->refundAmount = $this->selectedRma->refund_amount ?? 0;
    }

    public function closeDetailPanel()
    {
        $this->activeAction = null;
        $this->selectedRma = null;
        $this->reset(['adminNotes', 'resolutionAction', 'refundAmount']);
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
                    'resolution_type' => $this->resolutionAction,
                    'admin_notes' => $this->adminNotes,
                    'refund_amount' => $this->resolutionAction === 'refund' ? $this->refundAmount : 0,
                ]);

                // 2. Inventory Impact (Only for Replace)

                // 3. Refund Logic
                if ($this->resolutionAction === 'refund') {
                    $activeRegister = CashRegister::where('user_id', Auth::id())
                        ->where('status', 'open')
                        ->first();

                    if (! $activeRegister) {
                        throw new \Exception('Gagal: Anda harus membuka sesi Kasir (Cash Register) terlebih dahulu untuk memproses Refund Tunai.');
                    }

                    CashTransaction::create([
                        'cash_register_id' => $activeRegister->id,
                        'transaction_number' => 'REF-'.time(),
                        'type' => 'out',
                        'category' => 'refund',
                        'amount' => $this->refundAmount,
                        'description' => 'Refund RMA #'.$this->selectedRma->rma_number,
                        'reference_type' => Rma::class,
                        'reference_id' => $this->selectedRma->id,
                        'created_by' => Auth::id(),
                    ]);
                }
            });

            $this->dispatch('notify', message: 'RMA diselesaikan (Resolved).', type: 'success');
            $this->closeDetailPanel();

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $rmas = Rma::with('pengguna')
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn ($q) => $q->where('rma_number', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(10);

        return view('livewire.rma.manager', [
            'rmas' => $rmas,
        ]);
    }
}
