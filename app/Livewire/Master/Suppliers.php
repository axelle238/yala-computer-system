<?php

namespace App\Livewire\Master;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Master Supplier - Yala Computer')]
class Suppliers extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.suppliers', ['suppliers' => $suppliers]);
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Supplier dihapus.', type: 'info');
    }
}
