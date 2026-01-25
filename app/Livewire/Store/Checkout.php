<?php

namespace App\Livewire\Store;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\Payment\LayananMidtrans;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Checkout Aman - Yala Computer')]
class Checkout extends Component
{
    // Data Formulir
    public $nama;
    public $telepon;
    public $alamat;
    public $kota;
    public $kurir = 'jne'; // jne, jnt, sicepat
    public $poinUntukDitukar = 0;
    public $metodePembayaran = 'midtrans'; // midtrans, transfer_manual
    public $catatanPesanan = '';
    
    // Logika Voucher
    public $kodeVoucher = '';
    public $voucherTerpasang = null; 
    public $diskonVoucher = 0;

    // Logika Buku Alamat
    public $alamatTersimpan = [];
    public $idAlamatTerpilih = null;

    // Data Kalkulasi
    public $itemKeranjang = [];
    public $subtotal = 0;
    public $biayaPengiriman = 0;
    public $jumlahDiskon = 0;
    public $totalAkhir = 0;

    // Data Statis Kota & Ongkir
    public $daftarKota = [
        'Jakarta' => 10000,
        'Bogor' => 15000,
        'Depok' => 15000,
        'Tangerang' => 15000,
        'Bekasi' => 15000,
        'Bandung' => 20000,
        'Surabaya' => 25000,
        'Semarang' => 30000,
        'Yogyakarta' => 30000,
        'Medan' => 35000,
        'Denpasar' => 45000,
        'Makassar' => 45000,
        'Lainnya' => 50000,
    ];

    public function mount()
    {
        $this->muatKeranjang();
        
        if (empty($this->itemKeranjang)) {
            return redirect()->route('home');
        }

        if (Auth::check()) {
            $this->alamatTersimpan = \App\Models\UserAddress::where('user_id', Auth::id())->get();
            $utama = $this->alamatTersimpan->where('is_primary', true)->first();
            if ($utama) {
                $this->pilihAlamat($utama->id);
            } else {
                $this->nama = Auth::user()->name;
                $this->telepon = Auth::user()->phone ?? '';
            }
        }
    }

    public function pilihAlamat($id)
    {
        $alamat = $this->alamatTersimpan->where('id', $id)->first();
        if ($alamat) {
            $this->idAlamatTerpilih = $id;
            $this->nama = $alamat->recipient_name;
            $this->telepon = $alamat->phone_number;
            $this->alamat = $alamat->address_line;
            $this->kota = $alamat->city;
            $this->hitungTotal();
        }
    }

    public function bersihkanPilihanAlamat()
    {
        $this->idAlamatTerpilih = null;
        $this->nama = Auth::check() ? Auth::user()->name : '';
        $this->telepon = Auth::check() ? (Auth::user()->phone ?? '') : '';
        $this->alamat = '';
        $this->kota = '';
        $this->hitungTotal();
    }

    public function muatKeranjang()
    {
        $keranjang = Session::get('cart', []);
        $this->itemKeranjang = [];
        $this->subtotal = 0;

        if (!empty($keranjang)) {
            $produk = Product::whereIn('id', array_keys($keranjang))->get();
            foreach ($produk as $p) {
                $qty = $keranjang[$p->id];
                $totalBaris = $p->sell_price * $qty;
                
                $this->itemKeranjang[] = [
                    'produk' => $p,
                    'qty' => $qty,
                    'total_baris' => $totalBaris
                ];
                $this->subtotal += $totalBaris;
            }
        }
        $this->hitungTotal();
    }

    public function updatedKota() { $this->hitungTotal(); }
    public function updatedKurir() { $this->hitungTotal(); }

    public function pasangVoucher()
    {
        $this->validate(['kodeVoucher' => 'required|string']);

        $voucher = Voucher::where('code', $this->kodeVoucher)->first();

        if (!$voucher) {
            $this->addError('kodeVoucher', 'Kode voucher tidak valid.');
            return;
        }

        if (!$voucher->isValidForUser(Auth::id(), $this->subtotal)) {
            $this->addError('kodeVoucher', 'Voucher tidak dapat digunakan (Min. belanja / Kuota habis).');
            return;
        }

        $this->voucherTerpasang = $voucher;
        $this->hitungTotal();
        $this->dispatch('notify', message: 'Voucher berhasil dipasang!', type: 'success');
    }

    public function hapusVoucher()
    {
        $this->voucherTerpasang = null;
        $this->kodeVoucher = '';
        $this->diskonVoucher = 0;
        $this->hitungTotal();
    }

    public function updatedPoinUntukDitukar()
    {
        if (!Auth::check()) {
            $this->poinUntukDitukar = 0;
            return;
        }
        $maksPoin = Auth::user()->points;
        if ($this->poinUntukDitukar > $maksPoin) $this->poinUntukDitukar = $maksPoin;
        if ($this->poinUntukDitukar < 0) $this->poinUntukDitukar = 0;
        
        $this->hitungTotal();
    }

    public function hitungTotal()
    {
        // 1. Pengiriman
        $totalBerat = 0;
        foreach ($this->itemKeranjang as $item) {
            $berat = $item['produk']->weight > 0 ? $item['produk']->weight : 1000;
            $totalBerat += ($berat * $item['qty']);
        }
        $totalBeratKg = ceil($totalBerat / 1000);
        if ($totalBeratKg < 1) $totalBeratKg = 1;

        $biayaDasar = $this->daftarKota[$this->kota] ?? 0;
        $this->biayaPengiriman = $biayaDasar * $totalBeratKg;
        
        // 2. Voucher
        if ($this->voucherTerpasang) {
            if ($this->subtotal >= $this->voucherTerpasang->min_spend) {
                $this->diskonVoucher = $this->voucherTerpasang->calculateDiscount($this->subtotal);
            } else {
                $this->voucherTerpasang = null; 
                $this->diskonVoucher = 0;
            }
        } else {
            $this->diskonVoucher = 0;
        }

        // 3. Poin
        $this->jumlahDiskon = $this->poinUntukDitukar;

        // 4. Total Akhir
        $this->totalAkhir = $this->subtotal + $this->biayaPengiriman - $this->diskonVoucher - $this->jumlahDiskon;
        if ($this->totalAkhir < 0) $this->totalAkhir = 0;
    }

    /**
     * Memproses pesanan baru.
     */
    public function buatPesanan(LayananMidtrans $layananPembayaran)
    {
        $this->validate([
            'nama' => 'required|string|min:3',
            'telepon' => 'required|string|min:8',
            'alamat' => 'required|string|min:10',
            'kota' => 'required|string',
            'kurir' => 'required|string',
        ], [
            'nama.required' => 'Nama penerima wajib diisi.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'kota.required' => 'Kota tujuan wajib dipilih.',
            'kurir.required' => 'Jasa kurir wajib dipilih.',
        ]);

        if (empty($this->itemKeranjang)) return;

        $pesanan = DB::transaction(function () {
            $pesanan = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'guest_name' => $this->nama,
                'guest_whatsapp' => $this->telepon,
                'shipping_address' => $this->alamat,
                'shipping_city' => $this->kota,
                'shipping_courier' => $this->kurir,
                'shipping_cost' => $this->biayaPengiriman,
                'points_redeemed' => $this->poinUntukDitukar,
                'discount_amount' => $this->jumlahDiskon,
                'voucher_code' => $this->voucherTerpasang?->code,
                'voucher_discount' => $this->diskonVoucher,
                'total_amount' => $this->totalAkhir,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $this->catatanPesanan ?? 'Checkout via Website',
            ]);

            if ($this->voucherTerpasang) {
                VoucherUsage::create([
                    'voucher_id' => $this->voucherTerpasang->id,
                    'user_id' => Auth::id(),
                    'order_id' => $pesanan->id,
                    'discount_amount' => $this->diskonVoucher,
                    'used_at' => now(),
                ]);
            }

            foreach ($this->itemKeranjang as $item) {
                OrderItem::create([
                    'order_id' => $pesanan->id,
                    'product_id' => $item['produk']->id,
                    'quantity' => $item['qty'],
                    'price' => $item['produk']->sell_price,
                ]);
                $item['produk']->decrement('stock_quantity', $item['qty']);
            }

            if (Auth::check() && $this->poinUntukDitukar > 0) {
                Auth::user()->decrement('points', $this->poinUntukDitukar);
            }

            // Tangani Permintaan Perakitan PC
            if (Session::has('pc_assembly_data')) {
                $dataRakitan = Session::get('pc_assembly_data');
                
                \App\Models\PcAssembly::create([
                    'order_id' => $pesanan->id,
                    'build_name' => $dataRakitan['build_name'],
                    'status' => 'queued',
                    'specs_snapshot' => json_encode($dataRakitan['specs']),
                ]);

                Session::forget('pc_assembly_data');
            }

            Session::forget('cart');
            return $pesanan;
        });

        // Dapatkan Token Snap
        try {
            $dataSnap = $layananPembayaran->ambilTokenSnap($pesanan);
            $pesanan->update([
                'snap_token' => $dataSnap['token'],
                'payment_url' => $dataSnap['redirect_url']
            ]);

            $this->dispatch('trigger-payment', token: $dataSnap['token'], orderId: $pesanan->id);
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Kesalahan Pembayaran: ' . $e->getMessage(), type: 'error');
            return redirect()->route('order.success', $pesanan->id);
        }
    }

    public function render()
    {
        return view('livewire.store.checkout');
    }
}
