<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Kelola Flash Sale - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        FlashSale::find($id)->delete();
        $this->dispatch('notify', message: 'Flash sale item deleted.', type: 'success');
    }

    public function toggleStatus($id)
    {
        $sale = FlashSale::find($id);
        $sale->update(['is_active' => !$sale->is_active]);
    }

    public function render()
    {
        $sales = FlashSale::with('product')
            ->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.flash-sale.index', [
            'sales' => $sales
        ]);
    }
}
