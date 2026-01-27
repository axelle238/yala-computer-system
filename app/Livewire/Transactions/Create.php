<?php

namespace App\Livewire\Transactions;

use App\Models\CashRegister;
use App\Models\Commission;
use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Models\SavedBuild;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Point of Sales - Yala Computer')]
class Create extends Component
{
    // Informasi Transaksi
    public $tipe = 'out'; // 'out' (Penjualan), 'in' (Pembelian/Masuk), 'adjustment', 'return'

    public $telepon_pelanggan = '';

    public $nomor_referensi = '';

    public $catatan = '';

    // Sistem Keranjang
    public $keranjang = []; // Array dari ['product_id', 'name', 'price', 'quantity', 'subtotal', 'sku', 'image']

    // Pencarian & Filter
    public $cari = '';

    public $kategori = '';

    public $cariRakitan = '';

    // Sistem Loyalitas
    public $poinPelanggan = 0;

    public $gunakanPoin = false;

    // Status UI
    public $statusKasir = 'closed'; // open, closed

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        $this->nomor_referensi = 'TRX-'.strtoupper(uniqid());
        $this->periksaKasir();
    }

    /**
     * Memastikan sesi kasir aktif sebelum memulai transaksi penjualan.
     */
    public function periksaKasir()
    {
        $aktif = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->exists();

        $this->statusKasir = $aktif ? 'open' : 'closed';
    }

    /**
     * Memuat komponen dari rakitan PC yang sudah disimpan.
     */
    public function muatRakitan()
    {
        $rakitan = SavedBuild::where('id', $this->cariRakitan)
            ->orWhere('share_token', $this->cariRakitan)
            ->first();

        if (! $rakitan) {
            $this->dispatch('notify', message: 'Rakitan tidak ditemukan.', type: 'error');

            return;
        }

        $komponen = $rakitan->components;
        $jumlahDimuat = 0;

        foreach ($komponen as $key => $idProduk) {
            if ($idProduk) {
                $produk = Product::find($idProduk);
                if ($produk && $produk->stock_quantity > 0) {
                    $this->tambahKeKeranjang($produk->id);
                    $jumlahDimuat++;
                }
            }
        }

        if ($jumlahDimuat > 0) {
            $this->dispatch('notify', message: "Berhasil memuat {$jumlahDimuat} komponen dari rakitan '{$rakitan->name}'.", type: 'success');
            $this->cariRakitan = '';
        } else {
            $this->dispatch('notify', message: 'Semua komponen dalam rakitan ini stoknya habis.', type: 'error');
        }
    }

    /**
     * Mencari data member berdasarkan nomor telepon.
     */
    public function updatedTeleponPelanggan()
    {
        if (strlen($this->telepon_pelanggan) >= 10) {
            $pelanggan = Customer::where('phone', $this->telepon_pelanggan)->first();
            if ($pelanggan) {
                $this->poinPelanggan = $pelanggan->points;
                $this->dispatch('notify', message: 'Member ditemukan! Poin: '.number_format($pelanggan->points), type: 'info');
            } else {
                $this->poinPelanggan = 0;
            }
        } else {
            $this->poinPelanggan = 0;
            $this->gunakanPoin = false;
        }
    }

    /**
     * Mengaktifkan/menonaktifkan penggunaan poin loyalitas sebagai diskon.
     */
    public function tukarPoin()
    {
        if ($this->poinPelanggan <= 0) {
            return;
        }
        $this->gunakanPoin = ! $this->gunakanPoin;
    }

    /**
     * Mendukung pemindaian barcode langsung pada kolom pencarian.
     */
    public function updatedCari()
    {
        $produkEksak = Product::where('sku', $this->cari)
            ->orWhere('barcode', $this->cari)
            ->first();

        if ($produkEksak) {
            $this->tambahKeKeranjang($produkEksak->id);
            $this->cari = '';
            $this->dispatch('play-beep');
        }
    }

    /**
     * Menambahkan produk ke dalam antrean keranjang.
     */
    public function tambahKeKeranjang($idProduk)
    {
        $produk = Product::find($idProduk);

        if (! $produk) {
            return;
        }

        $kunciItemAda = null;
        foreach ($this->keranjang as $key => $item) {
            if ($item['product_id'] == $idProduk) {
                $kunciItemAda = $key;
                break;
            }
        }

        if ($kunciItemAda !== null) {
            $this->perbaruiJumlah($kunciItemAda, $this->keranjang[$kunciItemAda]['quantity'] + 1);
        } else {
            $this->keranjang[] = [
                'product_id' => $produk->id,
                'name' => $produk->name,
                'sku' => $produk->sku,
                'price' => $produk->sell_price,
                'buy_price' => $produk->buy_price,
                'quantity' => 1,
                'image' => $produk->image_path,
                'max_stock' => $produk->stock_quantity,
                'subtotal' => $produk->sell_price,
                'warranty_period' => $produk->warranty_period ?? 0,
                'serial_numbers' => [''],
            ];
        }
    }

    /**
     * Menghapus produk dari keranjang.
     */
    public function hapusDariKeranjang($index)
    {
        unset($this->keranjang[$index]);
        $this->keranjang = array_values($this->keranjang);
    }

    /**
     * Memperbarui kuantitas produk dalam keranjang dengan validasi stok.
     */
    public function perbaruiJumlah($index, $jumlah)
    {
        if (! isset($this->keranjang[$index])) {
            return;
        }

        $jumlahBaru = max(1, intval($jumlah));

        if ($this->tipe === 'out' && $jumlahBaru > $this->keranjang[$index]['max_stock']) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!', type: 'error');
            $jumlahBaru = $this->keranjang[$index]['max_stock'];
        }

        $this->keranjang[$index]['quantity'] = $jumlahBaru;
        $this->keranjang[$index]['subtotal'] = $jumlahBaru * $this->keranjang[$index]['price'];

        $snSaatIni = $this->keranjang[$index]['serial_numbers'] ?? [];
        if ($jumlahBaru > count($snSaatIni)) {
            for ($i = count($snSaatIni); $i < $jumlahBaru; $i++) {
                $snSaatIni[] = '';
            }
        } elseif ($jumlahBaru < count($snSaatIni)) {
            $snSaatIni = array_slice($snSaatIni, 0, $jumlahBaru);
        }
        $this->keranjang[$index]['serial_numbers'] = $snSaatIni;
    }

    /**
     * Mencatat nomor seri (Serial Number) untuk setiap unit produk.
     */
    public function perbaruiSerial($index, $snIndex, $nilai)
    {
        $this->keranjang[$index]['serial_numbers'][$snIndex] = $nilai;
    }

    /**
     * Menghitung total harga sebelum pajak dan diskon.
     */
    public function getSubtotalProperty()
    {
        return array_sum(array_column($this->keranjang, 'subtotal'));
    }

    /**
     * Menghitung nilai diskon dari penukaran poin member.
     */
    public function getDiscountProperty()
    {
        if ($this->gunakanPoin && $this->poinPelanggan > 0) {
            return min($this->poinPelanggan, $this->subtotal);
        }

        return 0;
    }

    /**
     * Menghitung Pajak Pertambahan Nilai (PPN) berdasarkan pengaturan sistem.
     */
    public function getTaxProperty()
    {
        $tarifPajak = Setting::get('tax_rate', 0);
        if ($tarifPajak > 0) {
            return ($this->subtotal - $this->discount) * ($tarifPajak / 100);
        }

        return 0;
    }

    /**
     * Menghitung total akhir yang harus dibayarkan pelanggan.
     */
    public function getTotalProperty()
    {
        return max(0, $this->subtotal + $this->tax - $this->discount);
    }

    /**
     * Menyimpan transaksi ke database dan menyesuaikan inventaris.
     */
    public function simpan()
    {
        if (empty($this->keranjang)) {
            $this->dispatch('notify', message: 'Keranjang kosong!', type: 'error');

            return;
        }

        $this->validate([
            'tipe' => 'required|in:in,out,adjustment,return',
            'catatan' => 'nullable|string|max:255',
        ]);

        $kasirAktif = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (! $kasirAktif && $this->tipe === 'out') {
            $this->dispatch('notify', message: 'Shift Kasir belum dibuka!', type: 'error');

            return redirect()->route('admin.shift.buka');
        }

        if ($this->gunakanPoin && $this->poinPelanggan > 0) {
            $pelanggan = Customer::where('phone', $this->telepon_pelanggan)->first();
            if (! $pelanggan || $pelanggan->points < $this->discount) {
                $this->addError('telepon_pelanggan', 'Saldo poin tidak valid atau berubah.');

                return;
            }
        }

        DB::transaction(function () use ($kasirAktif) {
            $pesanan = null;
            if ($this->tipe === 'out') {
                $catatanTransaksi = $this->catatan;
                if ($this->discount > 0) {
                    $catatanTransaksi .= " [Tukar {$this->discount} Poin]";
                }

                $pesanan = Order::create([
                    'user_id' => Auth::id(),
                    'cash_register_id' => $kasirAktif->id,
                    'guest_name' => $this->telepon_pelanggan ? 'Member '.$this->telepon_pelanggan : 'Tamu',
                    'guest_whatsapp' => $this->telepon_pelanggan,
                    'order_number' => $this->nomor_referensi,
                    'total_amount' => $this->total,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => 'cash',
                    'notes' => $catatanTransaksi,
                ]);

                if ($this->discount > 0 && $this->telepon_pelanggan) {
                    $pelanggan = Customer::where('phone', $this->telepon_pelanggan)->first();
                    $pelanggan->decrement('points', $this->discount);
                }
            }

            foreach ($this->keranjang as $item) {
                $produk = Product::lockForUpdate()->find($item['product_id']);
                if (! $produk) {
                    continue;
                }

                $jumlahTerjual = $item['quantity'];

                // Logika Paket (Bundle)
                if ($produk->is_bundle) {
                    $komponen = ProductBundle::where('parent_product_id', $produk->id)->get();

                    if ($komponen->isEmpty()) {
                        $this->prosesPenguranganStok($produk, $jumlahTerjual, $kasirAktif, $pesanan);
                    } else {
                        foreach ($komponen as $k) {
                            $produkAnak = Product::lockForUpdate()->find($k->child_product_id);
                            if ($produkAnak) {
                                $jumlahDibutuhkan = $k->quantity * $jumlahTerjual;
                                $this->prosesPenguranganStok($produkAnak, $jumlahDibutuhkan, $kasirAktif, $pesanan, "Paket: {$produk->name}");
                            }
                        }
                    }
                } else {
                    $this->prosesPenguranganStok($produk, $jumlahTerjual, $kasirAktif, $pesanan);
                }

                // Siapkan Nomor Seri
                $daftarSn = array_filter($item['serial_numbers'] ?? []);
                $stringSn = ! empty($daftarSn) ? implode(',', $daftarSn) : null;

                // Buat Item Pesanan
                if ($pesanan) {
                    $akhirGaransi = null;
                    if (! empty($item['warranty_period']) && $item['warranty_period'] > 0) {
                        $akhirGaransi = Carbon::now()->addMonths($item['warranty_period']);
                    }

                    OrderItem::create([
                        'order_id' => $pesanan->id,
                        'product_id' => $produk->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'serial_numbers' => $stringSn,
                        'warranty_ends_at' => $akhirGaransi,
                    ]);
                }

                if ($this->tipe === 'out' && $this->telepon_pelanggan) {
                    $pelanggan = Customer::firstOrCreate(
                        ['phone' => $this->telepon_pelanggan],
                        ['name' => 'Member '.$this->telepon_pelanggan, 'email' => $this->telepon_pelanggan.'@member.com']
                    );

                    $poinDidapat = floor($item['subtotal'] / 100000);
                    if ($poinDidapat > 0) {
                        $pelanggan->increment('points', $poinDidapat);
                    }
                }
            }

            // Hitung Komisi Sales
            if ($pesanan && Auth::check()) {
                $persen = Setting::get('commission_sales_percent', 1);
                $jumlahKomisi = $pesanan->total_amount * ($persen / 100);

                if ($jumlahKomisi > 0) {
                    Commission::create([
                        'user_id' => Auth::id(),
                        'amount' => $jumlahKomisi,
                        'description' => "Komisi Penjualan #{$pesanan->order_number} ({$persen}%)",
                        'source_type' => Order::class,
                        'source_id' => $pesanan->id,
                        'is_paid' => false,
                    ]);
                }
            }
        });

        $this->keranjang = [];
        $this->nomor_referensi = 'TRX-'.strtoupper(uniqid());
        $this->telepon_pelanggan = '';
        $this->catatan = '';

        $this->dispatch('notify', message: 'Transaksi berhasil disimpan!', type: 'success');
    }

    /**
     * Melakukan penyesuaian stok dan mencatat histori mutasi inventaris.
     */
    protected function prosesPenguranganStok($produk, $qty, $kasir, $pesanan, $awalanCatatan = '')
    {
        if ($this->tipe === 'out' && $produk->stock_quantity < $qty) {
            throw new \Exception("Stok {$produk->name} tidak mencukupi! Butuh: $qty, Sisa: {$produk->stock_quantity}");
        }

        $stokBaru = $produk->stock_quantity;
        if ($this->tipe === 'in' || $this->tipe === 'return') {
            $stokBaru += $qty;
        } else {
            $stokBaru -= $qty;
        }

        $produk->update(['stock_quantity' => $stokBaru]);

        InventoryTransaction::create([
            'product_id' => $produk->id,
            'user_id' => Auth::id() ?? 1,
            'type' => $this->tipe,
            'quantity' => $qty,
            'unit_price' => $produk->sell_price,
            'cogs' => $produk->buy_price,
            'remaining_stock' => $stokBaru,
            'reference_number' => $this->nomor_referensi,
            'notes' => trim($awalanCatatan.' '.$this->catatan),
        ]);
    }

    /**
     * Render antarmuka POS dengan filter pencarian dan kategori.
     */
    public function render()
    {
        $kueriProduk = Product::query()
            ->where('is_active', true)
            ->when($this->cari, function ($q) {
                $q->where('name', 'like', '%'.$this->cari.'%')
                    ->orWhere('sku', 'like', '%'.$this->cari.'%');
            })
            ->when($this->kategori, function ($q) {
                $q->where('category_id', $this->kategori);
            });

        return view('livewire.transactions.create', [
            'daftarProduk' => $kueriProduk->paginate(12),
            'daftarKategori' => \App\Models\Category::all(),
        ]);
    }
}
