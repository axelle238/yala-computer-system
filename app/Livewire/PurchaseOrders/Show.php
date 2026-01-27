<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\ActivityLog;
use App\Models\Expense;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Detail Pesanan Pembelian - Yala Computer')]
class Show extends Component
{
    /**
     * Objek Pesanan Pembelian (Purchase Order).
     */
    public PurchaseOrder $po;

    /**
     * Data input kuantitas barang yang diterima.
     */
    public $dataPenerimaan = []; // ['item_id' => qty_masuk]

    /**
     * Status panel aksi (null, 'terima').
     */
    public $aksiAktif = null;

    /**
     * Inisialisasi komponen.
     */
    public function mount(PurchaseOrder $po)
    {
        $this->po = $po->load(['item.produk', 'pemasok', 'pembuat']);

        // Inisialisasi data input penerimaan
        foreach ($this->po->item as $item) {
            $this->dataPenerimaan[$item->id] = 0;
        }
    }

    /**
     * Membuka panel untuk proses penerimaan barang fisik.
     */
    public function bukaPanelTerima()
    {
        // Isi otomatis dengan sisa barang yang belum diterima
        foreach ($this->po->item as $item) {
            $sisa = $item->quantity_ordered - $item->quantity_received;
            $this->dataPenerimaan[$item->id] = max(0, $sisa);
        }
        $this->aksiAktif = 'terima';
    }

    /**
     * Menutup panel penerimaan.
     */
    public function tutupPanelTerima()
    {
        $this->aksiAktif = null;
        $this->reset('dataPenerimaan');
        foreach ($this->po->item as $item) {
            $this->dataPenerimaan[$item->id] = 0;
        }
    }

    /**
     * Memproses penerimaan barang fisik ke gudang.
     */
    public function prosesPenerimaan()
    {
        $this->validate([
            'dataPenerimaan.*' => 'required|integer|min:0',
        ], [
            'dataPenerimaan.*.min' => 'Jumlah tidak boleh negatif.',
        ]);

        $totalBarangMasuk = array_sum($this->dataPenerimaan);
        if ($totalBarangMasuk <= 0) {
            $this->dispatch('notify', message: 'Silakan isi jumlah barang yang benar-benar diterima.', type: 'error');
            return;
        }

        try {
            DB::transaction(function () {
                $totalNilaiDiterima = 0;
                $semuaBarangSudahDiterima = true;
                $rincianLog = [];

                foreach ($this->po->item as $item) {
                    $qtyDiterima = (int) $this->dataPenerimaan[$item->id];
                    $sisaHarusDiterima = $item->quantity_ordered - $item->quantity_received;

                    if ($qtyDiterima > $sisaHarusDiterima) {
                        throw new \Exception("Jumlah terima untuk {$item->produk->name} melebihi sisa pesanan.");
                    }

                    if ($qtyDiterima > 0) {
                        // 1. Perbarui data item PO
                        $item->increment('quantity_received', $qtyDiterima);

                        // 2. Perbarui stok produk dan harga beli modal terbaru
                        $produk = Product::lockForUpdate()->find($item->product_id);
                        $produk->increment('stock_quantity', $qtyDiterima);
                        $produk->update(['buy_price' => $item->buy_price]);

                        // 3. Catat riwayat mutasi inventaris
                        InventoryTransaction::create([
                            'product_id' => $produk->id,
                            'user_id' => Auth::id(),
                            'type' => 'in',
                            'quantity' => $qtyDiterima,
                            'remaining_stock' => $produk->stock_quantity,
                            'reference_number' => $this->po->po_number,
                            'unit_price' => $item->buy_price,
                            'notes' => "Penerimaan Barang PO #{$this->po->po_number}",
                        ]);

                        $totalNilaiDiterima += ($qtyDiterima * $item->buy_price);
                        $rincianLog[] = "{$produk->name} ({$qtyDiterima} unit)";
                    }

                    // Periksa apakah masih ada sisa untuk barang ini
                    if ($item->refresh()->quantity_received < $item->quantity_ordered) {
                        $semuaBarangSudahDiterima = false;
                    }
                }

                // 4. Perbarui status pesanan pembelian
                if ($semuaBarangSudahDiterima) {
                    $this->po->update(['status' => 'received']);
                }

                // 5. Catat pengeluaran keuangan otomatis
                if ($totalNilaiDiterima > 0) {
                    Expense::create([
                        'user_id' => Auth::id(),
                        'category' => 'Pembelian Stok',
                        'title' => "Pembayaran PO #{$this->po->po_number} (Penerimaan Stok)",
                        'amount' => $totalNilaiDiterima,
                        'expense_date' => now(),
                    ]);
                }

                // 6. Catat log aktivitas manajerial
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'update',
                    'model_type' => PurchaseOrder::class,
                    'model_id' => $this->po->id,
                    'description' => "Menerima barang dari PO #{$this->po->po_number}: " . implode(', ', $rincianLog),
                    'ip_address' => request()->ip(),
                ]);
            });

            $this->tutupPanelTerima();
            $this->dispatch('notify', message: 'Penerimaan barang berhasil diproses dan stok telah diperbarui.', type: 'success');
            
            // Refresh data PO
            $this->po = $this->po->fresh(['item.produk', 'pemasok', 'pembuat']);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal memproses: ' . $e->getMessage(), type: 'error');
        }
    }

    /**
     * Menandai draf pesanan sebagai pesanan resmi (Ordered).
     */
    public function tandaiDipesan()
    {
        if ($this->po->status === 'draft') {
            $this->po->update(['status' => 'ordered']);
            
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model_type' => PurchaseOrder::class,
                'model_id' => $this->po->id,
                'description' => "Menyetujui dan mengirim PO #{$this->po->po_number} ke pemasok.",
                'ip_address' => request()->ip(),
            ]);

            $this->dispatch('notify', message: 'Status pesanan diperbarui menjadi Dipesan.', type: 'success');
        }
    }

    public function render()
    {
        return view('livewire.purchase-orders.show');
    }
}