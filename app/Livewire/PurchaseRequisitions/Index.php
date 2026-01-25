<?php

namespace App\Livewire\PurchaseRequisitions;

use App\Models\PurchaseRequisition;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Purchase Requisitions - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Stats Calculation
        $pendingCount = PurchaseRequisition::where('status', 'pending')->count();
        $approvedCount = PurchaseRequisition::where('status', 'approved')->count();
        $myRequestsCount = PurchaseRequisition::where('requested_by', auth()->id())->count();

        $requisitions = PurchaseRequisition::with('requester')
            ->where('pr_number', 'like', '%'.$this->search.'%')
            ->orWhereHas('requester', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
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
