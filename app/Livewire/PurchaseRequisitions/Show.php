<?php

namespace App\Livewire\PurchaseRequisitions;

use App\Models\PurchaseRequisition;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Detail Permintaan Stok - Yala Computer')]
class Show extends Component
{
    public PurchaseRequisition $pr;

    public function mount($id)
    {
        $this->pr = PurchaseRequisition::with(['items.product', 'requester', 'approver'])->findOrFail($id);
    }

    public function approve()
    {
        if (! auth()->user()->isAdmin()) {
            return abort(403);
        }

        $this->pr->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        session()->flash('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject()
    {
        if (! auth()->user()->isAdmin()) {
            return abort(403);
        }

        $this->pr->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        session()->flash('success', 'Pengajuan ditolak.');
    }

    public function convertToPo()
    {
        // Redirect to PO Create with PR data
        // Ideally we would pass ID and let PO Create handle it,
        // but for now let's just show a message or redirect to PO index
        // This part would be complex integration: Pre-filling PO form from PR.

        // For this step, I will mark it as converted and redirect
        $this->pr->update(['status' => 'converted_to_po']);

        return redirect()->route('purchase-orders.create', ['from_pr' => $this->pr->id]);
    }

    public function render()
    {
        return view('livewire.purchase-requisitions.show');
    }
}
