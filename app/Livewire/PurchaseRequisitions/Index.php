<?php

namespace App\Livewire\PurchaseRequisitions;

use App\Models\PurchaseRequisition;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Daftar Permintaan Stok - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public function render()
    {
        // Stats Calculation
        $pendingCount = PurchaseRequisition::where('status', 'pending')->count();
        $approvedCount = PurchaseRequisition::where('status', 'approved')->count();
        $myRequestsCount = PurchaseRequisition::where('requested_by', auth()->id())->count();

        $requisitions = PurchaseRequisition::with('pengaju')
            ->where('pr_number', 'like', '%'.$this->cari.'%')
            ->orWhereHas('pengaju', function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.purchase-requisitions.index', [
            'requisitions' => $requisitions,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'myRequestsCount' => $myRequestsCount,
        ]);
    }
}
