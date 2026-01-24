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
#[Title('Stock Opname (Audit Gudang)')]
class StockOpname extends Component
{
    use WithPagination;

    // View State
    public $activeOpname = null; // Jika ada sesi yang sedang berjalan
    public $viewMode = 'list'; // list, create, counting, review

    // Create Input
    public $notes = '';

    // Counting Input
    public $counts = []; // [item_id => physical_qty]

    public function mount()
    {
        $this->checkActiveOpname();
    }

    public function checkActiveOpname()
    {
        // Cari status counting atau review milik user ini (atau global jika warehouse logic diterapkan)
        $this->activeOpname = StockOpnameModel::whereIn('status', ['counting', 'review'])
            ->latest()
            ->first();

        if ($this->activeOpname) {
            $this->viewMode = $this->activeOpname->status;
            // Load counts for binding
            foreach ($this->activeOpname->items as $item) {
                $this->counts[$item->id] = $item->physical_stock; 
            }
        }
    }

    // --- Actions ---

    public function startNewOpname()
    {
        // Pastikan tidak ada opname berjalan
        if (StockOpnameModel::whereIn('status', ['counting', 'review'])->exists()) {
            $this->addError('global', 'Ada proses Stock Opname yang belum selesai. Selesaikan dulu.');
            return;
        }

        DB::transaction(function () {
            // 1. Create Header
            $opname = StockOpnameModel::create([
                'opname_number' => 'SO-' . date('Ymd') . '-' . rand(100, 999),
                'opname_date' => now(),
                'creator_id' => Auth::id(),
                'warehouse_id' => 1, // Default warehouse
                'status' => 'counting',
                'notes' => $this->notes,
            ]);

            // 2. Snapshot Stock (Hanya Barang Fisik)
            // Asumsi: Produk fisik adalah yang tidak berkategori 'Services' (seperti logika Workbench)
            $products = Product::whereHas('category', function ($q) {
                $q->where('slug', '!=', 'services');
            })->get();

            foreach ($products as $product) {
                StockOpnameItem::create([
                    'stock_opname_id' => $opname->id,
                    'product_id' => $product->id,
                    'system_stock' => $product->stock_quantity,
                    'physical_stock' => null, // Belum dihitung
                    'difference' => 0,
                ]);
            }

            $this->activeOpname = $opname;
            $this->viewMode = 'counting';
        });

        session()->flash('success', 'Sesi Stock Opname dimulai. Silakan mulai menghitung.');
    }

    public function saveCounts()
    {
        if (!$this->activeOpname) return;

        DB::transaction(function () {
            foreach ($this->counts as $itemId => $qty) {
                if ($qty === '' || $qty === null) continue; // Skip empty inputs

                $item = StockOpnameItem::find($itemId);
                if ($item && $item->stock_opname_id == $this->activeOpname->id) {
                    $item->update([
                        'physical_stock' => $qty,
                        'difference' => $qty - $item->system_stock,
                    ]);
                }
            }
        });

        session()->flash('success', 'Hasil hitung disimpan.');
    }

    public function finishCounting()
    {
        $this->saveCounts();
        
        // Cek apakah semua item sudah diisi (opsional, bisa partial)
        // $uncounted = $this->activeOpname->items()->whereNull('physical_stock')->count();
        
        $this->activeOpname->update(['status' => 'review']);
        $this->viewMode = 'review';
        $this->activeOpname->refresh();
    }

    public function finalizeAdjustment()
    {
        if (!$this->activeOpname || $this->activeOpname->status !== 'review') return;

        DB::transaction(function () {
            // 1. Process Adjustments
            $itemsWithDiff = $this->activeOpname->items()
                ->whereNotNull('physical_stock')
                ->where('difference', '!=', 0)
                ->get();

            foreach ($itemsWithDiff as $item) {
                $product = Product::find($item->product_id);
                
                // Update Stock
                // Jika difference positif (Fisik > Sistem) -> Tambah Stok
                // Jika difference negatif (Fisik < Sistem) -> Kurang Stok
                
                // Adjustment Transaction
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => 1,
                    'type' => 'adjustment',
                    'quantity' => abs($item->difference), // Selalu positif di quantity transaction?
                    // Atau simpan signed quantity? Biasanya inventory log quantity itu magnitude pergerakan.
                    // Tapi type 'adjustment' perlu tahu arahnya.
                    // Mari kita lihat definisi InventoryTransaction seeder/migration sebelumnya.
                    // Usually 'in'/'out' handles direction. For adjustment, we can use +/- logic if flexible, 
                    // or better: map to IN/OUT based on sign.
                    
                    // Logic: Difference +5 means we found 5 more. That is 'in' (adjustment in).
                    // Difference -5 means 5 missing. That is 'out' (adjustment out).
                    
                    'type' => $item->difference > 0 ? 'in' : 'out', 
                    
                    'remaining_stock' => $item->physical_stock, // Stok akhir harus sama dengan fisik
                    'unit_price' => $product->buy_price, 
                    'reference_number' => $this->activeOpname->opname_number,
                    'notes' => 'Stock Opname Adjustment: ' . ($item->difference > 0 ? "Found excess" : "Missing/Lost"),
                ]);

                $product->stock_quantity = $item->physical_stock;
                $product->save();
            }

            // 2. Close Opname
            $this->activeOpname->update([
                'status' => 'completed',
                'approver_id' => Auth::id(), // Auto approve by creator for now
            ]);
        });

        session()->flash('success', 'Stock Opname selesai! Stok telah disesuaikan.');
        $this->reset(['activeOpname', 'viewMode', 'counts']);
    }

    public function cancelOpname()
    {
        if ($this->activeOpname) {
            $this->activeOpname->update(['status' => 'cancelled']);
        }
        $this->reset(['activeOpname', 'viewMode', 'counts']);
    }

    public function render()
    {
        $history = StockOpnameModel::with('creator')->latest()->paginate(10);
        
        return view('livewire.warehouses.stock-opname', [
            'history' => $history
        ]);
    }
}