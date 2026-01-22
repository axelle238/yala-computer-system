<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Flash Sale - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public function delete($id)
    {
        FlashSale::findOrFail($id)->delete();
        session()->flash('success', 'Promo dihapus.');
    }

    public function render()
    {
        $sales = FlashSale::with('product')->latest()->paginate(10);
        return view('livewire.marketing.flash-sale.index', ['sales' => $sales]);
    }
}
