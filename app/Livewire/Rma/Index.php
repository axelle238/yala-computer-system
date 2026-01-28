<?php

namespace App\Livewire\Rma;

use App\Models\Rma;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen RMA & Garansi - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $cari = '';

    public $status = '';

    public function updatingCari()
    {
        $this->resetPage();
    }

    public function render()
    {
        $rmas = Rma::with('pengguna', 'pesanan')
            ->when($this->cari, function ($q) {
                $q->where('rma_number', 'like', '%'.$this->cari.'%')
                    ->orWhere('guest_name', 'like', '%'.$this->cari.'%');
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
