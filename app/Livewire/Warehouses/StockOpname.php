<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StockOpname as OpnameModel;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Stock Opname (Audit) - Yala Computer')]
class StockOpname extends Component
{
    public $viewMode = 'list'; // list, create, show
    
    // Create Mode
    public $opnameDate;
    public $notes;
    public $searchProduct = '';
    public $items = []; // [product_id => [system, physical, diff, notes]]

    // Show Mode
    public $selectedOpname;

    public function mount()
    {
        $this->opnameDate = date('Y-m-d');
    }

    public function toggleMode($mode)
    {
        $this->viewMode = $mode;
        if ($mode === 'create') {
            $this->items = [];
            $this->notes = '';
        }
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        if ($product && !isset($this->items[$productId])) {
            $this->items[$productId] = [
                'name' => $product->name,
                'sku' => $product->sku,
                'system' => $product->stock_quantity,
                'physical' => $product->stock_quantity, // Default to match
                'diff' => 0,
                'notes' => ''
            ];
        }
    }

    public function updatePhysical($productId, $val)
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['physical'] = (int) $val;
            $this->items[$productId]['diff'] = $this->items[$productId]['physical'] - $this->items[$productId]['system'];
        }
    }

    public function save()
    {
        $this->validate([
            'opnameDate' => 'required|date',
            'items' => 'required|array|min:1'
        ]);

        DB::transaction(function () {
            $opname = OpnameModel::create([
                'opname_number' => 'SO-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'creator_id' => Auth::id(),
                'opname_date' => $this->opnameDate,
                'status' => 'pending_approval', // Changed from submitted
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $pid => $data) {
                StockOpnameItem::create([
                    'stock_opname_id' => $opname->id,
                    'product_id' => $pid,
                    'system_stock' => $data['system'],
                    'physical_stock' => $data['physical'],
                    'difference' => $data['diff'],
                    'notes' => $data['notes'] ?? '',
                ]);
            }
        });

        $this->dispatch('notify', message: 'Stock Opname berhasil disimpan.', type: 'success');
        $this->toggleMode('list');
    }

    public function approve($id)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isOwner()) return;

        $opname = OpnameModel::with('items')->findOrFail($id);
        if ($opname->status === 'approved') return;

        DB::transaction(function () use ($opname) {
            foreach ($opname->items as $item) {
                if ($item->difference != 0) {
                    // Update Product Stock
                    $product = Product::find($item->product_id);
                    $product->stock_quantity = $item->physical_stock;
                    $product->save();

                    // Create Adjustment Transaction
                    InventoryTransaction::create([
                        'product_id' => $item->product_id,
                        'user_id' => Auth::id(),
                        'type' => 'adjustment',
                        'quantity' => $item->difference, // Can be negative
                        'remaining_stock' => $item->physical_stock,
                        'reference_number' => $opname->opname_number,
                        'notes' => 'Stock Opname Adjustment',
                    ]);
                }
            }

            $opname->update(['status' => 'approved', 'approver_id' => Auth::id()]);
        });

        $this->dispatch('notify', message: 'Opname disetujui & stok diperbarui.', type: 'success');
    }

    public function render()
    {
        $opnames = [];
        $searchProducts = [];

        if ($this->viewMode === 'list') {
            $opnames = OpnameModel::with('creator')->latest()->paginate(10);
        }

        if ($this->viewMode === 'create' && strlen($this->searchProduct) > 2) {
            $searchProducts = Product::where('name', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                ->take(5)->get();
        }

        return view('livewire.warehouses.stock-opname', [
            'opnames' => $opnames,
            'searchProducts' => $searchProducts
        ]);
    }
}