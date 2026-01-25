<?php

namespace App\Livewire\Inventory;

use App\Models\ProductSerial;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Pelacak Serial Number')]
class SerialTracer extends Component
{
    public $search = '';
    public $serial = null;

    public function searchSerial()
    {
        $this->validate(['search' => 'required|string|min:3']);
        
        $this->serial = ProductSerial::with(['product', 'purchaseOrder.supplier', 'order.user'])
            ->where('serial_number', $this->search)
            ->first();

        if (!$this->serial) {
            $this->dispatch('notify', message: 'Serial Number tidak ditemukan.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.inventory.serial-tracer');
    }
}
