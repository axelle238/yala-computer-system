<?php

namespace App\Livewire\Warehouses;

use App\Models\CashRegister;
use App\Models\CashTransaction;
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
#[Title('Manajemen Stok Opname - Yala Computer')]
class StockOpname extends Component
{
    use WithPagination;

    // Filter
    public $kataKunciCari = '';

    // Status Sesi
    public ?ModelStokOpname $opnameBerjalan = null;

    // Konfirmasi Finalisasi
    public $modeKonfirmasi = false;
    public $ringkasanFinal = [
        'total_item_selisih' => 0,
        'total_surplus' => 0,
        'total_kerugian' => 0,
        'net_finansial' => 0,
    ];

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        $this->opnameBerjalan = ModelStokOpname::where('creator_id', Auth::id())
            ->where('status', 'menghitung')
            ->latest()
            ->first();
    }

    /**
     * Menyiapkan ringkasan sebelum finalisasi.
     */
    public function siapkanFinalisasi()
    {
        if (!$this->opnameBerjalan) return;

        $itemBermasalah = $this->opnameBerjalan->item()
            ->whereNotNull('physical_stock')
            ->whereRaw('physical_stock != system_stock')
            ->with('produk')
            ->get();

        $surplus = 0;
        $kerugian = 0;
        $count = 0;

        foreach ($itemBermasalah as $item) {
            $selisih = $item->physical_stock - $item->system_stock;
            $nilai = abs($selisih) * ($item->produk->buy_price ?? 0);

            if ($selisih > 0) {
                $surplus += $nilai;
            } else {
                $kerugian += $nilai;
            }
            $count++;
        }

        $this->ringkasanFinal = [
            'total_item_selisih' => $count,
            'total_surplus' => $surplus,
            'total_kerugian' => $kerugian,
            'net_finansial' => $surplus - $kerugian,
        ];

        $this->modeKonfirmasi = true;
    }

    /**
     * Membatalkan mode konfirmasi.
     */
    public function batalKonfirmasi()
    {
        $this->modeKonfirmasi = false;
        $this->ringkasanFinal = [];
    }

    /**
     * Memulai sesi perhitungan stok baru.
     */
    public function bukaSesiOpname()
    {
        $gudang = Warehouse::first();

        if (! $gudang) {
            $this->dispatch('notify', message: 'Error: Data gudang utama tidak ditemukan.', type: 'error');

            return;
        }

        $this->opnameBerjalan = ModelStokOpname::create([
            'opname_number' => 'OPN-'.date('Ymd-His').'-'.Auth::id(),
            'warehouse_id' => $gudang->id,
            'creator_id' => Auth::id(),
            'status' => 'menghitung',
            'opname_date' => now(),
        ]);

        // Snapshoot stok sistem saat ini
        $produkAktif = Product::where('is_active', true)->get();
        $daftarInput = [];
        foreach ($produkAktif as $p) {
            $daftarInput[] = [
                'stock_opname_id' => $this->opnameBerjalan->id,
                'product_id' => $p->id,
                'system_stock' => $p->stock_quantity,
                'physical_stock' => null,
                'variance' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ItemStokOpname::insert($daftarInput);

        $this->dispatch('notify', message: 'Sesi opname dimulai. Silakan isi stok fisik.', type: 'info');
    }

    /**
     * Membatalkan sesi yang sedang berjalan.
     */
    public function hapusSesiIni()
    {
        if ($this->opnameBerjalan) {
            $this->opnameBerjalan->update(['status' => 'dibatalkan']);
            $this->opnameBerjalan = null;
            $this->dispatch('notify', message: 'Sesi opname telah dibatalkan.', type: 'warning');
        }
    }

    /**
     * Menyimpan input stok fisik secara real-time.
     */
    public function updateFisik($idItem, $jumlah)
    {
        $item = ItemStokOpname::find($idItem);
        if ($item && $this->opnameBerjalan && $item->stock_opname_id == $this->opnameBerjalan->id) {
            $item->update(['physical_stock' => $jumlah === '' ? null : $jumlah]);
        }
    }

    /**
     * Finalisasi Opname: Sesuaikan Stok & Catat Kerugian Finansial (Kompleks).
     */
    public function selesaikanDanFinalisasi()
    {
        if (! $this->opnameBerjalan) {
            return;
        }

        $kasirAktif = CashRegister::where('user_id', Auth::id())->where('status', 'open')->first();

        try {
            DB::transaction(function () use ($kasirAktif) {
                $itemBermasalah = $this->opnameBerjalan->item()
                    ->whereNotNull('physical_stock')
                    ->whereRaw('physical_stock != system_stock')
                    ->get();

                $totalKerugianFinansial = 0;

                foreach ($itemBermasalah as $item) {
                    $produk = $item->produk;
                    $selisih = $item->physical_stock - $item->system_stock;

                    // 1. Perbarui Stok Utama
                    $produk->update(['stock_quantity' => $item->physical_stock]);

                    // 2. Catat Log Audit Inventaris
                    InventoryTransaction::create([
                        'product_id' => $produk->id,
                        'user_id' => Auth::id(),
                        'warehouse_id' => $this->opnameBerjalan->warehouse_id,
                        'type' => 'adjustment',
                        'quantity' => abs($selisih),
                        'remaining_stock' => $item->physical_stock,
                        'notes' => "Penyesuaian Opname #{$this->opnameBerjalan->opname_number}. Selisih: {$selisih}",
                        'reference_number' => $this->opnameBerjalan->opname_number,
                    ]);

                    // 3. Hitung Kerugian (Hanya jika stok fisik < stok sistem)
                    if ($selisih < 0) {
                        $kerugianItem = abs($selisih) * ($produk->buy_price ?? 0);
                        $totalKerugianFinansial += $kerugianItem;
                    }
                }

                // 4. Integrasi Keuangan: Catat sebagai Beban jika ada kerugian & kasir buka
                if ($totalKerugianFinansial > 0 && $kasirAktif) {
                    CashTransaction::create([
                        'cash_register_id' => $kasirAktif->id,
                        'transaction_number' => 'BEBAN-OPNAME-'.$this->opnameBerjalan->id,
                        'type' => 'out',
                        'category' => 'expense',
                        'amount' => $totalKerugianFinansial,
                        'description' => "Kerugian Selisih Stok Opname #{$this->opnameBerjalan->opname_number}",
                        'reference_id' => $this->opnameBerjalan->id,
                        'reference_type' => ModelStokOpname::class,
                        'created_by' => Auth::id(),
                    ]);
                }

                $this->opnameBerjalan->update([
                    'status' => 'selesai',
                    'notes' => 'Finalisasi sistem otomatis. Total kerugian tercatat: Rp '.number_format($totalKerugianFinansial),
                ]);

                $this->opnameBerjalan = null;
            });

            $this->dispatch('notify', message: 'Stok opname berhasil difinalisasi. Stok sistem telah diperbarui.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal melakukan finalisasi: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $daftarItem = null;
        if ($this->opnameBerjalan) {
            $daftarItem = ItemStokOpname::where('stock_opname_id', $this->opnameBerjalan->id)
                ->with('produk')
                ->whereHas('produk', function ($q) {
                    $q->where('name', 'like', '%'.$this->kataKunciCari.'%')
                        ->orWhere('sku', 'like', '%'.$this->kataKunciCari.'%');
                })
                ->paginate(50);
        }

        return view('livewire.warehouses.stock-opname', [
            'daftarItem' => $daftarItem,
        ]);
    }
}
