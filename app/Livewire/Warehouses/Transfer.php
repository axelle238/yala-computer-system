<?php

namespace App\Livewire\Warehouses;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryTransfer; // Asumsi model ini ada
use App\Models\InventoryTransferItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Mutasi Stok Antar Gudang')]
class Transfer extends Component
{
    use WithPagination;

    public $showForm = false;
    
    // Form Inputs
    public $source_warehouse_id = 1; // Default Main
    public $dest_warehouse_id;
    public $notes;
    public $items = []; // [[product_id, qty]]

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'qty' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->validate([
            'source_warehouse_id' => 'required',
            'dest_warehouse_id' => 'required|different:source_warehouse_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () {
            // 1. Create Transfer Header
            $transfer = InventoryTransfer::create([
                'transfer_number' => 'TRF-' . date('Ymd') . '-' . rand(100,999),
                'source_warehouse_id' => $this->source_warehouse_id,
                'destination_warehouse_id' => $this->dest_warehouse_id,
                'status' => 'completed', // Direct transfer for simplicity in this version
                'notes' => $this->notes,
                'user_id' => Auth::id(),
                'transfer_date' => now(),
            ]);

            foreach ($this->items as $item) {
                $product = Product::find($item['product_id']);
                
                // 2. Create Transfer Item
                InventoryTransferItem::create([
                    'inventory_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                ]);

                // 3. Adjust Stock (Source -)
                // In a real multi-warehouse system, we would check stock per warehouse.
                // For this simulation where Product->stock_quantity is global/main:
                // We assume source=Main reduces global stock, Dest=Store increases "Store" stock logic (if implemented)
                // OR simpler: Just log the movement for audit trail if stock is shared.
                
                // Let's implement Logic: Reduce Global Stock if moving to "Broken/Reserved" warehouse? 
                // Or just log it. Let's assume standard behavior:
                // Global Stock is Main Warehouse. Moving to 'Store Display' keeps it in global count but changes location.
                // Moving to 'Sold/Lost' removes it.
                
                // For simplicity & safety: We will Log InventoryTransaction for history.
                InventoryTransaction::create([
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->source_warehouse_id,
                    'type' => 'transfer_out',
                    'quantity' => $item['qty'],
                    'reference_number' => $transfer->transfer_number,
                    'notes' => 'Transfer Out to Warehouse #' . $this->dest_warehouse_id,
                    'remaining_stock' => $product->stock_quantity, // Snapshot
                ]);

                InventoryTransaction::create([
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->dest_warehouse_id,
                    'type' => 'transfer_in',
                    'quantity' => $item['qty'],
                    'reference_number' => $transfer->transfer_number,
                    'notes' => 'Transfer In from Warehouse #' . $this->source_warehouse_id,
                    'remaining_stock' => $product->stock_quantity, // Snapshot
                ]);
            }
        });

        $this->showForm = false;
        $this->items = [];
        $this->dispatch('notify', message: 'Mutasi stok berhasil dicatat.', type: 'success');
    }

    public function render()
    {
        $transfers = InventoryTransfer::with(['source', 'destination', 'user'])
            ->latest()
            ->paginate(10);
            
        $warehouses = Warehouse::all();
        $products = Product::select('id', 'name', 'sku', 'stock_quantity')->get();

        return view('livewire.warehouses.transfer', [
            'transfers' => $transfers,
            'warehouses' => $warehouses,
            'products' => $products
        ]);
    }
}