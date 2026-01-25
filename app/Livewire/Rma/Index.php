<?php

namespace App\Livewire\Rma;

use App\Models\Rma;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen RMA - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $rmas = Rma::with('user', 'order')
            ->when($this->search, function ($q) {
                $q->where('rma_number', 'like', '%'.$this->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$this->search.'%');
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.rma.index', [
            'rmas' => $rmas,
        ]);
    }
}
