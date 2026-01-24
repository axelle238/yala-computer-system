<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Transfer Stok - Yala Computer')]
class Transfer extends Component
{
    use WithPagination;

    // View Mode: 'list' or 'create'
    public $viewMode = 'list';

    // Form Properties
    public $source_warehouse_id;
    public $destination_warehouse_id;
    public $notes;
    public $transferItems = []; // [['product_id' => '', 'qty' => 1]]

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->source_warehouse_id = '';
        $this->destination_warehouse_id = '';
        $this->notes = '';
        $this->transferItems = [['product_id' => '', 'qty' => 1]];
    }

    public function toggleMode()
    {
        $this->viewMode = $this->viewMode === 'list' ? 'create' : 'list';
        $this->resetForm();
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

        DB::transaction(function () {
            $transfer = InventoryTransfer::create([
                'transfer_number' => 'TRF-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'source_warehouse_id' => $this->source_warehouse_id,
                'destination_warehouse_id' => $this->destination_warehouse_id,
                'requested_by' => Auth::id(),
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            foreach ($this->transferItems as $item) {
                InventoryTransferItem::create([
                    'inventory_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                ]);
            }
        });

        $this->dispatch('notify', message: 'Pengajuan transfer stok berhasil dibuat!', type: 'success');
        $this->toggleMode(); // Kembali ke list
    }

    public function approve($id)
    {
        // Role check (simple version)
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'owner') {
             $this->dispatch('notify', message: 'Anda tidak memiliki akses approval.', type: 'error');
             return;
        }

        $transfer = InventoryTransfer::with('items.product')->findOrFail($id);

        if ($transfer->status !== 'pending') {
            return;
        }

        DB::transaction(function () use ($transfer) {
            foreach ($transfer->items as $item) {
                // 1. Out from Source
                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $transfer->source_warehouse_id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'remaining_stock' => $item->product->stock_quantity, // Simplified global stock
                    'reference_number' => $transfer->transfer_number,
                    'notes' => "Transfer Out to WH #{$transfer->destination_warehouse_id}",
                ]);

                // 2. In to Destination
                InventoryTransaction::create([
                    'product_id' => $item->product_id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $transfer->destination_warehouse_id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'remaining_stock' => $item->product->stock_quantity,
                    'reference_number' => $transfer->transfer_number,
                    'notes' => "Transfer In from WH #{$transfer->source_warehouse_id}",
                ]);
            }

            $transfer->update([
                'status' => 'approved',
                'approved_by' => Auth::id()
            ]);
        });

        $this->dispatch('notify', message: 'Transfer stok disetujui dan stok telah dimutasi.', type: 'success');
    }

    public function reject($id)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'owner') {
             $this->dispatch('notify', message: 'Anda tidak memiliki akses reject.', type: 'error');
             return;
        }

        $transfer = InventoryTransfer::findOrFail($id);
        if ($transfer->status === 'pending') {
            $transfer->update([
                'status' => 'rejected',
                'approved_by' => Auth::id()
            ]);
            $this->dispatch('notify', message: 'Pengajuan transfer ditolak.', type: 'success');
        }
    }

    public function render()
    {
        $transfers = InventoryTransfer::with(['sourceWarehouse', 'destinationWarehouse', 'requester'])
            ->latest()
            ->paginate(10);

        return view('livewire.warehouses.transfer', [
            'transfers' => $transfers,
            'products' => Product::where('is_active', true)->orderBy('name')->get(),
            'warehouses' => Warehouse::all(),
        ]);
    }
}
