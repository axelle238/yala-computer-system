<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
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
    public $isSessionActive = false;
    public $tempStock = []; // [product_id => physical_qty]

    public function mount()
    {
        // Cek jika ada sesi opname yang tersimpan di session browser (simple persistence)
        if (session()->has('opname_session_active')) {
            $this->isSessionActive = true;
            $this->tempStock = session()->get('opname_data', []);
        }
    }

    public function updatedTempStock()
    {
        session()->put('opname_data', $this->tempStock);
    }

    public function startSession()
    {
        $this->isSessionActive = true;
        $this->tempStock = [];
        session()->put('opname_session_active', true);
        session()->put('opname_data', []);
    }

    public function cancelSession()
    {
        $this->isSessionActive = false;
        $this->tempStock = [];
        session()->forget(['opname_session_active', 'opname_data']);
    }

    public function finalizeOpname()
    {
        // Validasi
        if (empty($this->tempStock)) {
            session()->flash('error', 'Belum ada data stok fisik yang diinput.');
            return;
        }

        DB::transaction(function () {
            $batchId = 'OPN-' . date('ymd-His');

            foreach ($this->tempStock as $productId => $physicalQty) {
                if ($physicalQty === '' || $physicalQty === null) continue;

                $product = Product::find($productId);
                if (!$product) continue;

                $systemQty = $product->stock_quantity;
                $variance = $physicalQty - $systemQty;

                if ($variance != 0) {
                    // Update Stok Produk
                    $product->stock_quantity = $physicalQty;
                    $product->save();

                    // Catat Transaksi
                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'warehouse_id' => 1, // Default warehouse
                        'type' => 'adjustment',
                        'quantity' => abs($variance), // Jumlah selisih mutlak
                        'unit_price' => $product->buy_price, // Pakai harga beli untuk HPP
                        'cogs' => $product->buy_price,
                        'remaining_stock' => $physicalQty,
                        'reference_number' => $batchId,
                        'notes' => "Stok Opname: System ($systemQty) -> Fisik ($physicalQty). Selisih: $variance",
                    ]);
                }
            }
        });

        session()->flash('success', 'Stok Opname Selesai! Stok telah disesuaikan.');
        $this->cancelSession(); // Reset session
    }

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(50); // Show more items for opname

        // Hitung Statistik Sesi Ini
        $stats = [
            'matched' => 0,
            'mismatch' => 0,
            'pending' => 0
        ];

        // Hitung manual karena pagination (hanya estimasi dari halaman yang diload atau data di tempStock vs All Products - simplifikasi untuk performa UI)
        // Idealnya query count, tapi karena tempStock ada di Livewire state (array), kita hitung yang ada di array saja
        foreach ($this->tempStock as $pid => $qty) {
            $p = Product::find($pid);
            if ($p) {
                if ($qty == $p->stock_quantity) $stats['matched']++;
                else $stats['mismatch']++;
            }
        }
        $stats['pending'] = $products->total() - count($this->tempStock);

        return view('livewire.warehouses.stock-opname', [
            'products' => $products,
            'stats' => $stats
        ]);
    }
}
