<?php

namespace App\Livewire\Services;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\Product;
use App\Models\ServiceTicket;
use App\Models\SukuCadangServis;
use App\Models\ServiceTicketProgress;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Meja Kerja Teknisi - Yala Computer')]
class Workbench extends Component
{
    /**
     * Data Tiket Servis.
     */
    public ServiceTicket $tiket;
    
    // Manajemen Status & Catatan
    public $statusSaatIni;
    public $inputCatatan = '';
    public $catatanPublik = true;

    // Manajemen Suku Cadang (Sparepart)
    public $cariSukuCadang = '';
    public $hasilPencarian = [];
    public $produkTerpilih = null;
    public $jumlah = 1;
    public $hargaKustom = 0;

    // Logika Pembayaran (Tanpa Modal)
    public $tampilkanFormPembayaran = false;
    public $metodePembayaran = 'tunai'; // tunai, transfer
    public $catatanPembayaran = '';

    public function mount($id)
    {
        $this->tiket = ServiceTicket::with([
            'parts.produk', 
            'progressLogs',
            'technician',
            'customerMember'
        ])->findOrFail($id);
        
        $this->statusSaatIni = $this->tiket->status;
    }

    /**
     * Pencarian produk/suku cadang secara real-time.
     */
    public function updatedCariSukuCadang()
    {
        if (strlen($this->cariSukuCadang) > 2) {
            $this->hasilPencarian = Product::with('category')
                ->where('is_active', true)
                ->where(function($q) {
                    $q->where('name', 'like', '%' . $this->cariSukuCadang . '%')
                      ->orWhere('sku', 'like', '%' . $this->cariSukuCadang . '%');
                })
                ->limit(5)
                ->get();
        } else {
            $this->hasilPencarian = [];
        }
    }

    /**
     * Memilih produk dari hasil pencarian.
     */
    public function pilihProduk($idProduk)
    {
        $this->produkTerpilih = Product::with('category')->find($idProduk);
        if ($this->produkTerpilih) {
            $this->hargaKustom = $this->produkTerpilih->sell_price; 
        }
        $this->cariSukuCadang = '';
        $this->hasilPencarian = [];
    }

    /**
     * Mengecek apakah produk perlu dilacak stoknya.
     */
    private function dipantauStoknya($produk)
    {
        // Jika kategori adalah 'jasa' atau 'layanan', tidak perlu kurangi stok fisik
        if ($produk->category && in_array($produk->category->slug, ['services', 'jasa', 'layanan'])) {
            return false;
        }
        return true;
    }

    /**
     * Menambahkan suku cadang ke tiket servis dan mengurangi stok.
     */
    public function tambahSukuCadang()
    {
        if (!$this->produkTerpilih) return;

        $this->validate([
            'jumlah' => 'required|integer|min:1',
            'hargaKustom' => 'required|numeric|min:0',
        ], [
            'jumlah.min' => 'Jumlah minimal adalah 1.',
            'hargaKustom.min' => 'Harga tidak boleh negatif.',
        ]);

        $lacakStok = $this->dipantauStoknya($this->produkTerpilih);

        if ($lacakStok && $this->produkTerpilih->stock_quantity < $this->jumlah) {
            $this->addError('jumlah', 'Stok tidak mencukupi (Tersedia: ' . $this->produkTerpilih->stock_quantity . ')');
            return;
        }

        DB::transaction(function () use ($lacakStok) {
            if ($lacakStok) {
                // Kurangi Stok Global
                $this->produkTerpilih->decrement('stock_quantity', $this->jumlah);
                
                // Catat Transaksi Inventaris
                InventoryTransaction::create([
                    'product_id' => $this->produkTerpilih->id,
                    'user_id' => Auth::id() ?? 1,
                    'warehouse_id' => 1, // Gudang Utama
                    'type' => 'out',
                    'quantity' => $this->jumlah,
                    'unit_price' => $this->hargaKustom,
                    'cogs' => $this->produkTerpilih->buy_price ?? 0,
                    'remaining_stock' => $this->produkTerpilih->stock_quantity, 
                    'reference_number' => $this->tiket->ticket_number,
                    'notes' => 'Digunakan dalam Tiket Servis #' . $this->tiket->ticket_number,
                ]);
            }

            // Simpan pemakaian suku cadang
            SukuCadangServis::create([
                'id_tiket_servis' => $this->tiket->id,
                'id_produk' => $this->produkTerpilih->id,
                'jumlah' => $this->jumlah,
                'harga_satuan' => $this->hargaKustom,
                'catatan' => 'Ditambahkan teknisi: ' . (Auth::user()->name ?? 'Sistem'),
            ]);
        });

        $this->produkTerpilih = null;
        $this->jumlah = 1;
        $this->hargaKustom = 0;
        
        $this->tiket->refresh();
        $this->dispatch('notify', message: 'Suku cadang berhasil ditambahkan.', type: 'success');
    }

    /**
     * Menghapus suku cadang dan mengembalikan stok.
     */
    public function hapusSukuCadang($idSukuCadang)
    {
        $item = SukuCadangServis::findOrFail($idSukuCadang);

        DB::transaction(function () use ($item) {
            $produk = Product::find($item->id_produk);
            $lacakStok = $produk && $this->dipantauStoknya($produk);

            if ($lacakStok) {
                $produk->increment('stock_quantity', $item->jumlah);

                InventoryTransaction::create([
                    'product_id' => $produk->id,
                    'user_id' => Auth::id() ?? 1,
                    'warehouse_id' => 1,
                    'type' => 'in',
                    'quantity' => $item->jumlah,
                    'unit_price' => $item->harga_satuan,
                    'remaining_stock' => $produk->stock_quantity,
                    'reference_number' => $this->tiket->ticket_number,
                    'notes' => 'Dibatalkan/Dikembalikan dari Tiket Servis #' . $this->tiket->ticket_number,
                ]);
            }

            $item->delete();
        });

        $this->tiket->refresh();
        $this->dispatch('notify', message: 'Suku cadang dihapus dan stok dikembalikan.', type: 'success');
    }

    /**
     * Menyimpan catatan progres servis.
     */
    public function simpanProgres()
    {
        $this->validate([
            'inputCatatan' => 'required|string|min:3',
        ], [
            'inputCatatan.required' => 'Catatan wajib diisi.',
            'inputCatatan.min' => 'Catatan minimal 3 karakter.',
        ]);

        ServiceTicketProgress::create([
            'service_ticket_id' => $this->tiket->id,
            'status_label' => $this->tiket->status,
            'description' => $this->inputCatatan,
            'technician_id' => Auth::id() ?? 1,
            'is_public' => $this->catatanPublik,
        ]);

        $this->inputCatatan = '';
        $this->tiket->refresh();
        $this->dispatch('notify', message: 'Catatan progres berhasil disimpan.', type: 'success');
    }

    /**
     * Memperbarui status tiket servis.
     */
    public function perbaruiStatus($statusBaru)
    {
        if ($this->tiket->status === $statusBaru) return;

        $statusLama = $this->tiket->status;
        $this->tiket->status = $statusBaru;
        $this->tiket->save();

        ServiceTicketProgress::create([
            'service_ticket_id' => $this->tiket->id,
            'status_label' => $statusBaru,
            'description' => "Status diubah dari " . ucfirst(str_replace('_', ' ', $statusLama)) . " ke " . ucfirst(str_replace('_', ' ', $statusBaru)),
            'technician_id' => Auth::id() ?? 1,
            'is_public' => true,
        ]);

        $this->statusSaatIni = $statusBaru;
        $this->tiket->refresh();
        $this->dispatch('notify', message: 'Status tiket berhasil diperbarui.', type: 'success');
    }

    /**
     * Memproses pembayaran tiket servis.
     */
    public function prosesPembayaran()
    {
        // 1. Cek Kasir Aktif
        $kasirAktif = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();
        
        if (!$kasirAktif) {
            $this->dispatch('notify', message: 'Sesi kasir belum dibuka! Silakan buka kasir terlebih dahulu.', type: 'error');
            return;
        }

        // 2. Hitung Total Tagihan (Sum dari SukuCadangServis)
        // Kita perlu menyesuaikan query ini karena nama tabel/model sudah berubah
        $totalTagihan = SukuCadangServis::where('id_tiket_servis', $this->tiket->id)->sum(DB::raw('jumlah * harga_satuan'));
        
        if ($totalTagihan <= 0) {
            $this->dispatch('notify', message: 'Tagihan Rp 0. Masukkan jasa atau sparepart dahulu.', type: 'error');
            return;
        }

        DB::transaction(function () use ($kasirAktif, $totalTagihan) {
            // Catat Transaksi Kas
            CashTransaction::create([
                'cash_register_id' => $kasirAktif->id,
                'transaction_number' => 'PAY-' . date('ymd') . '-' . $this->tiket->id,
                'type' => 'in',
                'category' => 'service_payment',
                'amount' => $totalTagihan,
                'description' => "Pembayaran Servis Tiket #{$this->tiket->ticket_number}. " . $this->catatanPembayaran,
                'reference_id' => $this->tiket->id,
                'reference_type' => ServiceTicket::class,
                'created_by' => Auth::id(),
            ]);

            // Selesaikan Tiket
            $this->tiket->status = 'picked_up';
            $this->tiket->final_cost = $totalTagihan;
            $this->tiket->save();

            // Log Progres Akhir
            ServiceTicketProgress::create([
                'service_ticket_id' => $this->tiket->id,
                'status_label' => 'picked_up',
                'description' => "Pembayaran diterima sebesar Rp " . number_format($totalTagihan) . " via " . ucfirst($this->metodePembayaran) . ". Unit telah diambil.",
                'technician_id' => Auth::id(),
                'is_public' => true,
            ]);
        });

        $this->tampilkanFormPembayaran = false;
        $this->statusSaatIni = 'picked_up';
        $this->tiket->refresh();
        $this->dispatch('notify', message: 'Pembayaran berhasil! Tiket telah diselesaikan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.services.workbench');
    }
}
