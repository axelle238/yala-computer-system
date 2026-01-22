<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Transfer Stok - Yala Computer')]
class Transfer extends Component
{
    public $product_id;
    public $from_warehouse_id;
    public $to_warehouse_id;
    public $quantity = 1;
    public $notes;

    public $products = [];
    public $warehouses = [];

    public function mount()
    {
        $this->products = Product::where('is_active', true)->get();
        $this->warehouses = Warehouse::where('is_active', true)->get();
    }

    public function transfer()
    {
        $this->validate([
            'product_id' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required|different:from_warehouse_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($this->product_id);
        
        // Cek stok di gudang asal
        $sourceStock = DB::table('product_warehouse')
            ->where('product_id', $this->product_id)
            ->where('warehouse_id', $this->from_warehouse_id)
            ->value('quantity');

        if (!$sourceStock || $sourceStock < $this->quantity) {
            $this->addError('quantity', 'Stok di gudang asal tidak mencukupi.');
            return;
        }

        DB::transaction(function () use ($product) {
            // Kurangi Gudang Asal
            DB::table('product_warehouse')
                ->where('product_id', $this->product_id)
                ->where('warehouse_id', $this->from_warehouse_id)
                ->decrement('quantity', $this->quantity);

            // Tambah Gudang Tujuan (Create if not exists)
            $exists = DB::table('product_warehouse')
                ->where('product_id', $this->product_id)
                ->where('warehouse_id', $this->to_warehouse_id)
                ->exists();

            if ($exists) {
                DB::table('product_warehouse')
                    ->where('product_id', $this->product_id)
                    ->where('warehouse_id', $this->to_warehouse_id)
                    ->increment('quantity', $this->quantity);
            } else {
                DB::table('product_warehouse')->insert([
                    'product_id' => $this->product_id,
                    'warehouse_id' => $this->to_warehouse_id,
                    'quantity' => $this->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Catat Log Transaksi (Adjustment)
            $fromName = Warehouse::find($this->from_warehouse_id)->name;
            $toName = Warehouse::find($this->to_warehouse_id)->name;

            InventoryTransaction::create([
                'product_id' => $this->product_id,
                'user_id' => auth()->id(),
                'type' => 'adjustment',
                'quantity' => 0, // Net quantity change secara global adalah 0
                'remaining_stock' => $product->stock_quantity, // Global stock tetap
                'notes' => "Transfer {$this->quantity} unit dari {$fromName} ke {$toName}. {$this->notes}",
            ]);
        });

        session()->flash('success', 'Transfer stok berhasil.');
        return redirect()->route('warehouses.transfer');
    }

    public function render()
    {
        return view('livewire.warehouses.transfer');
    }
}
