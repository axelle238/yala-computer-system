<?php

namespace App\Livewire\Warehouses;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\StockOpname as ModelStokOpname;
use App\Models\StockOpnameItem as ItemStokOpname;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Stok Opname - Yala Computer')]
class StockOpname extends Component
{
    use WithPagination;

    public $cari = '';

    public ?ModelStokOpname $opnameAktif = null;

    public function mount()
    {
        // Cari sesi opname yang sedang berlangsung untuk pengguna ini
        $this->opnameAktif = ModelStokOpname::where('creator_id', Auth::id())
            ->where('status', 'menghitung')
            ->latest()
            ->first();
    }

    public function mulaiSesi()
    {
        $gudangUtama = Warehouse::first();

        if (! $gudangUtama) {
            $this->dispatch('notify', message: 'Tidak ada data gudang ditemukan.', type: 'error');

            return;
        }

        // Buat sesi opname baru
        $this->opnameAktif = ModelStokOpname::create([
            'opname_number' => 'OPN-'.date('Ymd-His'),
            'warehouse_id' => $gudangUtama->id,
            'creator_id' => Auth::id(),
            'status' => 'menghitung',
            'opname_date' => now(),
        ]);

        // Masukkan semua produk aktif ke dalam item opname
        $produk = Product::where('is_active', true)->get();
        $item = [];
        foreach ($produk as $p) {
            $item[] = [
                'stock_opname_id' => $this->opnameAktif->id,
                'product_id' => $p->id,
                'system_stock' => $p->stock_quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ItemStokOpname::insert($item);
    }

    public function batalkanSesi()
    {
        if ($this->opnameAktif) {
            $this->opnameAktif->update(['status' => 'dibatalkan']);
            $this->opnameAktif = null;
        }
    }

    public function perbaruiStokFisik($idItem, $stokFisik)
    {
        $item = ItemStokOpname::find($idItem);
        if ($item && $this->opnameAktif && $item->stock_opname_id == $this->opnameAktif->id) {
            $item->update(['physical_stock' => $stokFisik === '' ? null : $stokFisik]);
        }
    }

    public function finalisasiOpname()
    {
        if (! $this->opnameAktif) {
            return;
        }

        DB::transaction(function () {
            $itemUntukDisesuaikan = $this->opnameAktif->items()
                ->whereNotNull('physical_stock')
                ->whereRaw('physical_stock != system_stock')
                ->get();

            foreach ($itemUntukDisesuaikan as $item) {
                $produk = $item->product;
                $selisih = $item->variance;

                // Perbarui Stok Produk
                $produk->update(['stock_quantity' => $item->physical_stock]);

                // Catat Transaksi
                InventoryTransaction::create([
                    'product_id' => $produk->id,
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->opnameAktif->warehouse_id,
                    'type' => 'adjustment',
                    'quantity' => abs($selisih),
                    'remaining_stock' => $item->physical_stock,
                    'notes' => "Stok Opname #{$this->opnameAktif->opname_number}: Selisih {$selisih}",
                    'reference_number' => $this->opnameAktif->opname_number,
                ]);
            }

            // Tandai sesi opname selesai
            $this->opnameAktif->update(['status' => 'selesai']);
            $this->opnameAktif = null;
        });

        session()->flash('success', 'Stok Opname Selesai! Stok telah disesuaikan.');
    }

    public function render()
    {
        $daftarItem = null;
        if ($this->opnameAktif) {
            $daftarItem = ItemStokOpname::where('stock_opname_id', $this->opnameAktif->id)
                ->with('product')
                ->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%'.$this->cari.'%')
                        ->orWhere('sku', 'like', '%'.$this->cari.'%');
                })
                ->paginate(50);
        }

        return view('livewire.warehouses.stock-opname', [
            'daftarItem' => $daftarItem,
        ]);
    }
}
