<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Transfer Stok - Yala Computer')]
class Transfer extends Component
{
    public $source_warehouse_id;
    public $destination_warehouse_id;
    public $notes;
    public $transferItems = []; // [['product_id' => '', 'qty' => 1]]

    public function mount()
    {
        // Init with one empty row
        $this->addItem();
    }

    public function addItem()
    {
        $this->transferItems[] = ['product_id' => '', 'qty' => 1];
    }

    public function removeItem($index)
    {
        unset($this->transferItems[$index]);
        $this->transferItems = array_values($this->transferItems);
    }

    public function save()
    {
        $this->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|exists:warehouses,id|different:source_warehouse_id',
            'notes' => 'nullable|string',
            'transferItems.*.product_id' => 'required|exists:products,id',
            'transferItems.*.qty' => 'required|integer|min:1',
        ]);

        if (empty($this->transferItems)) {
            $this->dispatch('notify', message: 'Tambahkan minimal satu barang.', type: 'error');
            return;
        }

        DB::transaction(function () {
            $refNumber = 'TRF-' . date('YmdHis');

            foreach ($this->transferItems as $item) {
                $product = Product::find($item['product_id']);
                
                // 1. Out from Source
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->source_warehouse_id,
                    'type' => 'out', // Transfer Out
                    'quantity' => $item['qty'],
                    'remaining_stock' => $product->stock_quantity, // Global stock doesn't strictly change in total if we just track location, but usually transfers don't affect global sellable count unless we separate it. For simplicity, we just log movement.
                    'unit_price' => $product->sell_price,
                    'cogs' => $product->buy_price,
                    'reference_number' => $refNumber,
                    'notes' => "Transfer Out to Warehouse #{$this->destination_warehouse_id}. " . $this->notes,
                ]);

                // 2. In to Destination
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->destination_warehouse_id,
                    'type' => 'in', // Transfer In
                    'quantity' => $item['qty'],
                    'remaining_stock' => $product->stock_quantity,
                    'unit_price' => $product->sell_price,
                    'cogs' => $product->buy_price,
                    'reference_number' => $refNumber,
                    'notes' => "Transfer In from Warehouse #{$this->source_warehouse_id}. " . $this->notes,
                ]);
            }
        });

        $this->transferItems = [['product_id' => '', 'qty' => 1]];
        $this->notes = '';
        $this->dispatch('notify', message: 'Transfer stok berhasil dicatat!', type: 'success');
    }

    public function render()
    {
        return view('livewire.warehouses.transfer', [
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::all(), // Will fail if model doesn't exist, handled in next step
        ]);
    }
}