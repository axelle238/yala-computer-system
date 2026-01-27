<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Models\SavedBuild;
use App\Services\PcCompatibilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.store')]
#[Title('Simulasi Rakit PC - Yala Computer')]
class PcBuilder extends Component
{
    use WithPagination;

    /**
     * Status Rakitan
     */
    public $namaRakitan = 'PC Kustom Saya';

    public $pilihanKomponen = [];

    public $totalHarga = 0;

    public $estimasiDaya = 0;

    public $tambahJasaRakit = false;

    /**
     * Pesan Status Kompatibilitas
     */
    public $masalahKompatibilitas = [];

    public $peringatanKompatibilitas = [];

    public $infoKompatibilitas = [];

    /**
     * Status Pemilih Komponen (Tanpa Modal)
     */
    public $tampilkanPemilih = false;

    public $kategoriSaatIni = null;

    public $kataKunciCari = '';

    /**
     * Daftar Kategori Komponen
     */
    public $daftarBagian = [
        'processors' => ['label' => 'Prosesor (CPU)', 'ikon' => 'cpu'],
        'motherboards' => ['label' => 'Motherboard', 'ikon' => 'server'],
        'rams' => ['label' => 'Memori (RAM)', 'ikon' => 'memory'],
        'gpus' => ['label' => 'Kartu Grafis (VGA)', 'ikon' => 'monitor'],
        'storage' => ['label' => 'Penyimpanan (SSD/HDD)', 'ikon' => 'hard-drive'],
        'cases' => ['label' => 'Casing PC', 'ikon' => 'box'],
        'psus' => ['label' => 'Power Supply (PSU)', 'ikon' => 'zap'],
        'coolers' => ['label' => 'Pendingin CPU', 'ikon' => 'wind'],
    ];

    public function mount()
    {
        // Inisialisasi pilihan kosong
        foreach (array_keys($this->daftarBagian) as $kunci) {
            $this->pilihanKomponen[$kunci] = null;
        }

        // Muat Rakitan dari Sesi (jika ada kloning)
        if (session()->has('cloned_build')) {
            $this->pilihanKomponen = session()->get('cloned_build');
            $this->namaRakitan = session()->get('cloned_build_name', 'PC Kustom Baru');
            $this->hitungUlang();

            session()->forget(['cloned_build', 'cloned_build_name']);
        }
    }

    /**
     * Membuka antarmuka pemilih komponen.
     */
    public function bukaPemilih($kategori)
    {
        $this->kategoriSaatIni = $kategori;
        $this->kataKunciCari = '';
        $this->tampilkanPemilih = true;
        $this->resetPage();
    }

    /**
     * Menutup antarmuka pemilih komponen.
     */
    public function tutupPemilih()
    {
        $this->tampilkanPemilih = false;
        $this->kategoriSaatIni = null;
    }

    /**
     * Memilih produk untuk kategori tertentu.
     */
    public function pilihProduk($idProduk)
    {
        $produk = Product::find($idProduk);
        if ($produk) {
            $this->pilihanKomponen[$this->kategoriSaatIni] = [
                'id' => $produk->id,
                'nama' => $produk->name,
                'harga' => $produk->sell_price,
                'gambar' => $produk->image_path,
                'spek' => $produk->specifications,
            ];
            $this->hitungUlang();
        }
        $this->tutupPemilih();
    }

    /**
     * Menghapus komponen dari pilihan.
     */
    public function hapusBagian($kategori)
    {
        $this->pilihanKomponen[$kategori] = null;
        $this->hitungUlang();
    }

    public function updatedTambahJasaRakit()
    {
        $this->hitungUlang();
    }

    /**
     * Menghitung total harga dan mengecek kompatibilitas.
     */
    public function hitungUlang()
    {
        $this->totalHarga = 0;

        // 1. Jumlahkan Harga
        foreach ($this->pilihanKomponen as $item) {
            if ($item) {
                $this->totalHarga += $item['harga'];
            }
        }

        // 2. Biaya Rakit
        if ($this->tambahJasaRakit) {
            $this->totalHarga += 150000;
        }

        // 3. Layanan Kompatibilitas
        $layanan = new PcCompatibilityService;
        $hasil = $layanan->checkCompatibility($this->pilihanKomponen);

        $this->masalahKompatibilitas = $hasil['issues'];
        $this->peringatanKompatibilitas = $hasil['warnings'];
        $this->infoKompatibilitas = $hasil['info'];
        $this->estimasiDaya = $hasil['wattage'];
    }

    /**
     * Menyimpan rakitan ke profil pengguna.
     */
    public function simpanRakitan()
    {
        if (! Auth::check()) {
            $this->dispatch('notify', message: 'Silakan masuk untuk menyimpan rakitan.', type: 'error');

            return redirect()->route('pelanggan.masuk');
        }

        SavedBuild::create([
            'user_id' => Auth::id(),
            'name' => $this->namaRakitan,
            'description' => 'Disimpan pada '.now()->locale('id')->isoFormat('D MMMM Y'),
            'total_price_estimated' => $this->totalHarga,
            'components' => $this->pilihanKomponen,
            'share_token' => Str::random(32),
        ]);

        $this->dispatch('notify', message: 'Rakitan berhasil disimpan ke profil Anda!', type: 'success');
    }

    /**
     * Memasukkan semua komponen ke keranjang belanja.
     */
    public function masukkanKeKeranjang()
    {
        $keranjang = session()->get('cart', []);
        $jumlahTerpilih = 0;

        foreach ($this->pilihanKomponen as $item) {
            if ($item) {
                $id = $item['id'];
                $keranjang[$id] = 1; // Sesuai format Checkout: ID => Qty
                $jumlahTerpilih++;
            }
        }

        if ($jumlahTerpilih > 0) {
            // Tangani Jasa Rakit
            if ($this->tambahJasaRakit) {
                $produkJasa = Product::firstOrCreate(
                    ['sku' => 'SVC-RAKIT'],
                    [
                        'name' => 'Jasa Rakit PC Professional',
                        'slug' => 'jasa-rakit-pc-professional',
                        'description' => 'Jasa perakitan, instalasi OS (Trial), dan manajemen kabel rapi.',
                        'sell_price' => 150000,
                        'buy_price' => 0,
                        'stock_quantity' => 9999,
                        'category_id' => 1,
                        'is_active' => true,
                    ]
                );

                $keranjang[$produkJasa->id] = 1;

                // Simpan Metadata Rakitan untuk dikelola di antrian teknisi
                session()->put('pc_assembly_data', [
                    'build_name' => $this->namaRakitan,
                    'specs' => $this->pilihanKomponen,
                    'wattage' => $this->estimasiDaya,
                ]);
            } else {
                session()->forget('pc_assembly_data');
            }

            session()->put('cart', $keranjang);
            $this->dispatch('cart-updated');

            return redirect()->route('toko.keranjang');
        } else {
            $this->dispatch('notify', message: 'Pilih komponen minimal satu terlebih dahulu.', type: 'warning');
        }
    }

    public function render()
    {
        $daftarProduk = [];
        if ($this->tampilkanPemilih && $this->kategoriSaatIni) {
            $daftarProduk = Product::where('is_active', true)
                ->whereHas('category', function ($q) {
                    $q->where('slug', $this->kategoriSaatIni);
                })
                ->where('name', 'like', '%'.$this->kataKunciCari.'%')
                ->orderBy('sell_price')
                ->paginate(12);
        }

        return view('livewire.store.pc-builder', [
            'daftarProduk' => $daftarProduk,
        ]);
    }
}
