<?php

namespace App\Livewire\Warehouses;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Mutasi Stok Antar Gudang - Yala Computer')]
class Transfer extends Component
{
    use WithPagination;

    public $tampilkanForm = false;
    
    // Input Form
    public $idGudangAsal;
    public $idGudangTujuan;
    public $catatan;
    public $daftarItem = []; // [[id_produk, qty]]

    public function mount()
    {
        // Set default gudang asal ke gudang pertama jika ada
        $gudangPertama = Warehouse::first();
        if ($gudangPertama) {
            $this->idGudangAsal = $gudangPertama->id;
        }
        $this->tambahItem();
    }

    public function tambahItem()
    {
        $this->daftarItem[] = ['id_produk' => '', 'qty' => 1];
    }

    public function hapusItem($index)
    {
        unset($this->daftarItem[$index]);
        $this->daftarItem = array_values($this->daftarItem);
    }

    public function buat()
    {
        $this->reset(['idGudangTujuan', 'catatan']);
        $this->daftarItem = [['id_produk' => '', 'qty' => 1]];
        $this->tampilkanForm = true;
    }

    public function simpan()
    {
        $this->validate([
            'idGudangAsal' => 'required',
            'idGudangTujuan' => 'required|different:idGudangAsal',
            'daftarItem' => 'required|array|min:1',
            'daftarItem.*.id_produk' => 'required',
            'daftarItem.*.qty' => 'required|integer|min:1',
        ], [
            'idGudangTujuan.required' => 'Gudang tujuan wajib dipilih.',
            'idGudangTujuan.different' => 'Gudang tujuan tidak boleh sama dengan gudang asal.',
            'daftarItem.*.id_produk.required' => 'Produk wajib dipilih.',
            'daftarItem.*.qty.min' => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () {
            // 1. Buat Header Transfer
            $transfer = InventoryTransfer::create([
                'transfer_number' => 'TRF-' . date('Ymd') . '-' . rand(100,999),
                'source_warehouse_id' => $this->idGudangAsal,
                'destination_warehouse_id' => $this->idGudangTujuan,
                'status' => 'completed', // Langsung selesai untuk versi ini
                'notes' => $this->catatan,
                'user_id' => Auth::id(),
                'transfer_date' => now(),
            ]);

            foreach ($this->daftarItem as $item) {
                $produk = Product::find($item['id_produk']);
                
                // 2. Buat Item Transfer
                InventoryTransferItem::create([
                    'inventory_transfer_id' => $transfer->id,
                    'product_id' => $item['id_produk'],
                    'quantity' => $item['qty'],
                ]);

                // 3. Sesuaikan Stok Fisik di Tabel Pivot (Warehouse Product)
                $gudangAsal = Warehouse::find($this->idGudangAsal);
                $gudangTujuan = Warehouse::find($this->idGudangTujuan);

                // Kurangi Asal
                $stokAsal = $gudangAsal->products()->where('product_id', $item['id_produk'])->first()->pivot->quantity ?? 0;
                $gudangAsal->products()->syncWithoutDetaching([
                    $item['id_produk'] => ['quantity' => max(0, $stokAsal - $item['qty'])]
                ]);

                // Tambah Tujuan
                $stokTujuan = $gudangTujuan->products()->where('product_id', $item['id_produk'])->first()->pivot->quantity ?? 0;
                $gudangTujuan->products()->syncWithoutDetaching([
                    $item['id_produk'] => ['quantity' => $stokTujuan + $item['qty']]
                ]);

                // 4. Catat Riwayat Transaksi
                InventoryTransaction::create([
                    'product_id' => $item['id_produk'],
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->idGudangAsal,
                    'type' => 'transfer_out',
                    'quantity' => $item['qty'],
                    'reference_number' => $transfer->transfer_number,
                    'notes' => 'Mutasi Keluar ke Gudang #' . $gudangTujuan->name,
                    'remaining_stock' => max(0, $stokAsal - $item['qty']),
                ]);

                InventoryTransaction::create([
                    'product_id' => $item['id_produk'],
                    'user_id' => Auth::id(),
                    'warehouse_id' => $this->idGudangTujuan,
                    'type' => 'transfer_in',
                    'quantity' => $item['qty'],
                    'reference_number' => $transfer->transfer_number,
                    'notes' => 'Mutasi Masuk dari Gudang #' . $gudangAsal->name,
                    'remaining_stock' => $stokTujuan + $item['qty'],
                ]);
            }
        });

        $this->tampilkanForm = false;
        $this->daftarItem = [['id_produk' => '', 'qty' => 1]];
        $this->dispatch('notify', message: 'Mutasi stok berhasil dicatat.', type: 'success');
    }

    public function render()
    {
        $riwayatMutasi = InventoryTransfer::with(['source', 'destination', 'user'])
            ->latest()
            ->paginate(10);
            
        $daftarGudang = Warehouse::all();
        $daftarProduk = Product::select('id', 'name', 'sku', 'stock_quantity')->get();

        return view('livewire.warehouses.transfer', [
            'riwayatMutasi' => $riwayatMutasi,
            'daftarGudang' => $daftarGudang,
            'daftarProduk' => $daftarProduk
        ]);
    }
}
