<?php

namespace App\Livewire\Quotations;

use App\Models\Quotation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Daftar Penawaran (B2B) - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $quotes = Quotation::with('customer')
            ->where('quote_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('customer', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.quotations.index', [
            'quotes' => $quotes,
            'sentCount' => Quotation::where('status', 'sent')->count(),
            'draftCount' => Quotation::where('status', 'draft')->count(),
            'convertedCount' => Quotation::where('status', 'converted')->count(),
        ]);
    }
}
