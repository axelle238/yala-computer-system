<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StockOpname as StockOpnameModel;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
#[Title('Stok Opname - Yala Computer')]
class StockOpname extends Component
{
    use WithPagination;

    public $search = '';
    public ?StockOpnameModel $activeOpname = null;

    public function mount()
    {
        // Cari sesi opname yang sedang berlangsung untuk user ini
        $this->activeOpname = StockOpnameModel::where('creator_id', Auth::id())
            ->where('status', 'counting')
            ->latest()
            ->first();
    }

    public function startSession()
    {
        // Buat sesi opname baru
        $this->activeOpname = StockOpnameModel::create([
            'opname_number' => 'OPN-' . date('Ymd-His'),
            'warehouse_id' => 1, // Asumsi gudang utama
            'creator_id' => Auth::id(),
            'status' => 'counting',
            'opname_date' => now(),
        ]);

        // Muat semua produk aktif ke dalam item opname
        $products = Product::where('is_active', true)->get();
        $items = [];
        foreach($products as $product) {
            $items[] = [
                'stock_opname_id' => $this->activeOpname->id,
                'product_id' => $product->id,
                'system_stock' => $product->stock_quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        StockOpnameItem::insert($items);
    }

    public function cancelSession()
    {
        if ($this->activeOpname) {
            $this->activeOpname->update(['status' => 'cancelled']);
            $this->activeOpname = null;
        }
    }
    
    public function updatePhysicalStock($itemId, $physicalStock)
    {
        $item = StockOpnameItem::find($itemId);
        if ($item && $this->activeOpname && $item->stock_opname_id == $this->activeOpname->id) {
            $item->update(['physical_stock' => $physicalStock === '' ? null : $physicalStock]);
        }
    }

    public function finalizeOpname()
    {
        if (!$this->activeOpname) return;

        DB::transaction(function () {
            $itemsToAdjust = $this->activeOpname->items()
                ->whereNotNull('physical_stock')
                ->whereRaw('physical_stock != system_stock')
                ->get();
            
            foreach ($itemsToAdjust as $item) {
                $product = $item->product;
                $variance = $item->variance;

                // Update Stok Produk
                $product->update(['stock_quantity' => $item->physical_stock]);

                // Catat Transaksi
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->activeOpname->warehouse_id,
                    'type' => 'adjustment',
                    'quantity' => abs($variance),
                    'remaining_stock' => $item->physical_stock,
                    'notes' => "Stok Opname #{$this->activeOpname->opname_number}: Selisih {$variance}",
                    'reference_number' => $this->activeOpname->opname_number,
                ]);
            }

            // Tandai sesi opname selesai
            $this->activeOpname->update(['status' => 'completed']);
            $this->activeOpname = null;
        });

        session()->flash('success', 'Stok Opname Selesai! Stok telah disesuaikan.');
    }

    public function render()
    {
        $items = null;
        if ($this->activeOpname) {
            $items = StockOpnameItem::where('stock_opname_id', $this->activeOpname->id)
                ->with('product')
                ->whereHas('product', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                })
                ->paginate(50);
        }

        return view('livewire.warehouses.stock-opname', [
            'items' => $items
        ]);
    }
}