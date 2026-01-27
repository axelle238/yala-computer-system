<?php

namespace App\Livewire\Sales;

use App\Models\CashRegister;
use App\Models\CashTransaction;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher; // Tambahkan Model Voucher
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Point of Sales (POS)')]
class PointOfSale extends Component
{
    // Cari & Filter
    public $kataKunciCari = '';
    public $idKategori = null;

    // Keranjang
    public $keranjang = []; 
    public $subtotal = 0;
    public $diskon = 0;
    public $totalAkhir = 0;

    // Voucher
    public $kodeVoucher = '';
    public $voucherTerpakai = null;

    // Pelanggan
    public $idMemberTerpilih = null;
    public $namaTamu = 'Tamu';
    public $cariMember = '';
    public $hasilCariMember = [];

    // Pembayaran
    public $metodePembayaran = 'tunai'; 
    public $uangDibayar = 0;
    public $kembalian = 0;

    // Status Sistem
    public $kasirAktif;

    public function mount()
    {
        $this->periksaKasir();
    }

    public function periksaKasir()
    {
        $this->kasirAktif = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (! $this->kasirAktif) {
            return redirect()->route('admin.keuangan.kasir')->with('error', 'Sesi kasir belum dibuka. Silakan buka shift terlebih dahulu.');
        }
    }

    // --- Logika Produk ---

    public function tambahKeKeranjang($idProduk)
    {
        $produk = Product::find($idProduk);

        if (! $produk) {
            return;
        }
        if ($produk->stock_quantity <= 0) {
            $this->dispatch('notify', message: 'Maaf, stok produk ini telah habis.', type: 'error');
            return;
        }

        if (isset($this->keranjang[$idProduk])) {
            if ($this->keranjang[$idProduk]['qty'] + 1 > $produk->stock_quantity) {
                $this->dispatch('notify', message: 'Jumlah melebihi stok yang tersedia saat ini.', type: 'error');
                return;
            }
            $this->keranjang[$idProduk]['qty']++;
        } else {
            $this->keranjang[$idProduk] = [
                'id' => $produk->id,
                'nama' => $produk->name,
                'sku' => $produk->sku,
                'harga' => $produk->sell_price,
                'qty' => 1,
                'stok_maks' => $produk->stock_quantity,
            ];
        }

        $this->hitungTotal();
        $this->kataKunciCari = '';
    }

    public function perbaruiJumlah($idProduk, $jumlah)
    {
        if (! isset($this->keranjang[$idProduk])) {
            return;
        }

        $jumlah = intval($jumlah);

        if ($jumlah <= 0) {
            unset($this->keranjang[$idProduk]);
        } else {
            if ($jumlah > $this->keranjang[$idProduk]['stok_maks']) {
                $this->dispatch('notify', message: 'Jumlah yang diminta melebihi stok gudang.', type: 'error');
                $jumlah = $this->keranjang[$idProduk]['stok_maks'];
            }
            $this->keranjang[$idProduk]['qty'] = $jumlah;
        }

        $this->hitungTotal();
    }

    public function hapusItem($idProduk)
    {
        unset($this->keranjang[$idProduk]);
        $this->hitungTotal();
    }

    // --- Logika Voucher & Diskon ---

    public function terapkanVoucher()
    {
        if (empty($this->kodeVoucher)) {
            $this->voucherTerpakai = null;
            $this->diskon = 0;
            $this->hitungTotal();
            return;
        }

        $voucher = Voucher::where('code', $this->kodeVoucher)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->first();

        if ($voucher) {
            if ($voucher->usage_limit > 0 && $voucher->usage_count >= $voucher->usage_limit) {
                $this->dispatch('notify', message: 'Kuota voucher telah habis.', type: 'error');
                return;
            }

            $this->voucherTerpakai = $voucher;
            $this->dispatch('notify', message: 'Voucher berhasil diterapkan!', type: 'success');
            $this->hitungTotal();
        } else {
            $this->dispatch('notify', message: 'Kode voucher tidak valid atau kedaluwarsa.', type: 'error');
            $this->voucherTerpakai = null;
            $this->diskon = 0;
            $this->hitungTotal();
        }
    }

    public function hapusVoucher()
    {
        $this->kodeVoucher = '';
        $this->voucherTerpakai = null;
        $this->diskon = 0;
        $this->hitungTotal();
    }

    public function hitungTotal()
    {
        $this->subtotal = 0;
        foreach ($this->keranjang as $item) {
            $this->subtotal += $item['harga'] * $item['qty'];
        }

        // Hitung Diskon Voucher
        if ($this->voucherTerpakai) {
            if ($this->voucherTerpakai->discount_type == 'percentage') {
                $this->diskon = ($this->subtotal * $this->voucherTerpakai->discount_value) / 100;
            } else {
                $this->diskon = $this->voucherTerpakai->discount_value;
            }
            // Pastikan diskon tidak melebihi subtotal
            $this->diskon = min($this->diskon, $this->subtotal);
        } else {
            $this->diskon = 0;
        }

        $this->totalAkhir = max(0, $this->subtotal - $this->diskon);
        $this->hitungKembalian();
    }

    public function updatedUangDibayar()
    {
        $this->hitungKembalian();
    }

    public function hitungKembalian()
    {
        if ($this->metodePembayaran == 'tunai') {
            $this->kembalian = max(0, floatval($this->uangDibayar) - $this->totalAkhir);
        } else {
            $this->kembalian = 0;
            $this->uangDibayar = $this->totalAkhir;
        }
    }

    // --- Logika Member ---

    public function updatedCariMember()
    {
        if (strlen($this->cariMember) > 2) {
            $this->hasilCariMember = User::where('name', 'like', '%'.$this->cariMember.'%')
                ->orWhere('email', 'like', '%'.$this->cariMember.'%')
                ->orWhere('phone', 'like', '%'.$this->cariMember.'%')
                ->limit(5)
                ->get();
        } else {
            $this->hasilCariMember = [];
        }
    }

    public function pilihMember($id)
    {
        $member = User::find($id);
        $this->idMemberTerpilih = $id;
        $this->namaTamu = $member->name;
        $this->cariMember = '';
        $this->hasilCariMember = [];
    }

    // --- Logika Checkout ---

    public function prosesCheckout()
    {
        if (empty($this->keranjang)) {
            $this->dispatch('notify', message: 'Daftar belanja masih kosong.', type: 'error');
            return;
        }

        if ($this->metodePembayaran == 'tunai' && $this->uangDibayar < $this->totalAkhir) {
            $this->dispatch('notify', message: 'Nominal pembayaran tidak mencukupi.', type: 'error');
            return;
        }

        foreach ($this->keranjang as $id => $item) {
            $produk = Product::find($id);
            if ($produk->stock_quantity < $item['qty']) {
                $this->dispatch('notify', message: "Stok produk {$produk->name} tidak mencukupi untuk transaksi ini.", type: 'error');
                return;
            }
        }

        try {
            DB::transaction(function () {
                $pesanan = Order::create([
                    'order_number' => 'ORD-'.date('ymd').'-'.rand(1000, 9999),
                    'cash_register_id' => $this->kasirAktif->id,
                    'user_id' => $this->idMemberTerpilih,
                    'guest_name' => $this->idMemberTerpilih ? null : $this->namaTamu,
                    'total_amount' => $this->totalAkhir,
                    'discount_amount' => $this->diskon,
                    'payment_method' => $this->metodePembayaran,
                    'payment_status' => 'paid',
                    'status' => 'completed',
                    'paid_at' => now(),
                    'notes' => $this->voucherTerpakai ? 'Voucher: ' . $this->voucherTerpakai->code : null,
                ]);

                foreach ($this->keranjang as $id => $item) {
                    $produk = Product::find($id);

                    OrderItem::create([
                        'order_id' => $pesanan->id,
                        'product_id' => $id,
                        'quantity' => $item['qty'],
                        'price' => $item['harga'],
                    ]);

                    $produk->decrement('stock_quantity', $item['qty']);

                    InventoryTransaction::create([
                        'product_id' => $produk->id,
                        'user_id' => Auth::id(),
                        'warehouse_id' => 1,
                        'type' => 'out',
                        'quantity' => $item['qty'],
                        'remaining_stock' => $produk->stock_quantity,
                        'unit_price' => $item['harga'],
                        'cogs' => $produk->buy_price,
                        'reference_number' => $pesanan->order_number,
                        'notes' => 'Transaksi Kasir (POS)',
                    ]);
                }

                // Catat Penggunaan Voucher
                if ($this->voucherTerpakai) {
                    $this->voucherTerpakai->increment('usage_count');
                }

                CashTransaction::create([
                    'cash_register_id' => $this->kasirAktif->id,
                    'transaction_number' => 'TRX-POS-'.$pesanan->id,
                    'type' => 'in',
                    'category' => 'sales',
                    'amount' => $this->totalAkhir,
                    'description' => "Penjualan Kasir #{$pesanan->order_number} ({$this->metodePembayaran})",
                    'reference_id' => $pesanan->id,
                    'reference_type' => Order::class,
                    'created_by' => Auth::id(),
                ]);

                $this->dispatch('print-receipt', orderId: $pesanan->id);
            });

            $this->reset(['keranjang', 'subtotal', 'diskon', 'totalAkhir', 'uangDibayar', 'kembalian', 'idMemberTerpilih', 'kodeVoucher', 'voucherTerpakai']);
            $this->namaTamu = 'Tamu';
            $this->dispatch('notify', message: 'Transaksi berhasil diproses dan stok telah diperbarui.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal memproses transaksi: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $produkList = Product::query()
            ->when($this->kataKunciCari, function ($q) {
                $q->where('name', 'like', '%'.$this->kataKunciCari.'%')
                    ->orWhere('sku', 'like', '%'.$this->kataKunciCari.'%');
            })
            ->when($this->idKategori, function ($q) {
                $q->where('category_id', $this->idKategori);
            })
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', '!=', 'services');
            })
            ->latest()
            ->paginate(12);

        $daftarKategori = \App\Models\Category::all();

        return view('livewire.sales.point-of-sale', [
            'produkList' => $produkList,
            'daftarKategori' => $daftarKategori,
        ]);
    }
}