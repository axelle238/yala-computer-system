<?php

namespace App\Livewire\Quotations;

use App\Models\Quotation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Daftar Penawaran (B2B) - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $quotes = Quotation::with('user')
            ->where('quotation_number', 'like', '%'.$this->search.'%')
            ->orWhereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.quotations.index', [
            'quotes' => $quotes,
            'pendingCount' => Quotation::where('approval_status', 'pending')->count(),
            'approvedCount' => Quotation::where('approval_status', 'approved')->count(),
            'convertedCount' => Quotation::whereNotNull('converted_order_id')->count(),
        ]);
    }
}
