<?php

namespace App\Livewire\Marketing\FlashSale;

use App\Models\FlashSale;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Flash Sale Manager - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public function toggleStatus($id)
    {
        $sale = FlashSale::find($id);
        if ($sale) {
            $sale->is_active = !$sale->is_active;
            $sale->save();
            $this->dispatch('notify', message: 'Status Flash Sale diperbarui.', type: 'success');
        }
    }

    public function render()
    {
        $flashSales = FlashSale::with('product')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('livewire.marketing.flash-sale.index', [
            'flashSales' => $flashSales
        ]);
    }
}