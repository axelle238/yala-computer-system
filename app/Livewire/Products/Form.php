<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Services\YalaIntelligence; // Import Service
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Formulir Produk - Yala Computer')]
class Form extends Component
{
    use WithFileUploads;

    public $name;
    public $category_id;
    public $description;
    public $buy_price;
    public $sell_price;
    public $stock_quantity;
    public $sku;
    
    // ... properti lain ...

    public function generateAiContent(YalaIntelligence $ai)
    {
        $this->validate([
            'name' => 'required|min:3',
            'category_id' => 'required'
        ], [
            'name.required' => 'Nama produk wajib diisi untuk generate konten.',
            'category_id.required' => 'Pilih kategori dulu ya.'
        ]);

        $kategori = Category::find($this->category_id)->name ?? 'Elektronik';
        
        // 1. Generate Deskripsi
        $this->description = $ai->generateDeskripsiProduk($this->name, $kategori);
        
        // 2. Rekomendasi Harga (Jika harga beli diisi)
        if ($this->buy_price > 0) {
            $analisisHarga = $ai->rekomendasiHarga($this->buy_price, $kategori);
            // Kita tidak langsung timpa, tapi beri notifikasi atau isi jika kosong
            if (empty($this->sell_price)) {
                $this->sell_price = $analisisHarga['rekomendasi'];
                $this->dispatch('notify', message: "AI: Harga jual diset ke Rp " . number_format($this->sell_price) . " (Margin optimal).", type: 'info');
            } else {
                $this->dispatch('notify', message: "AI Insight: " . $analisisHarga['analisis'], type: 'info');
            }
        }
        
        $this->dispatch('notify', message: 'Konten produk berhasil dibuat oleh Yala Brain!', type: 'success');
    }

    // ... sisa kode ...

    public function mount($id = null)
    {
        if ($id) {
            $this->produk = Product::findOrFail($id);
            $this->nama = $this->produk->name;
            $this->sku = $this->produk->sku;
            $this->barcode = $this->produk->barcode;
            $this->idKategori = $this->produk->category_id;
            $this->idPemasok = $this->produk->supplier_id;
            $this->hargaBeli = $this->produk->buy_price;
            $this->hargaJual = $this->produk->sell_price;
            $this->jumlahStok = $this->produk->stock_quantity;
            $this->peringatanStokMin = $this->produk->min_stock_alert;
            $this->deskripsi = $this->produk->description;
            $this->pathGambar = $this->produk->image_path;
        }
    }

    /**
     * Otomatis generate SKU saat nama berubah.
     */
    public function updatedNama()
    {
        if (empty($this->sku)) {
            $this->sku = strtoupper(Str::slug($this->nama));
        }
    }

    /**
     * Menyimpan data produk ke basis data.
     */
    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,'.($this->produk->id ?? 'NULL'),
            'idKategori' => 'required|exists:categories,id',
            'idPemasok' => 'nullable|exists:suppliers,id',
            'hargaBeli' => 'required|numeric|min:0',
            'hargaJual' => 'required|numeric|min:0',
            'jumlahStok' => 'required|integer|min:0',
            'peringatanStokMin' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:2048', // Maksimal 2MB
        ], [
            'nama.required' => 'Nama produk wajib diisi.',
            'sku.unique' => 'Kode SKU sudah digunakan.',
            'idKategori.required' => 'Kategori wajib dipilih.',
            'hargaBeli.min' => 'Harga beli tidak boleh negatif.',
            'hargaJual.min' => 'Harga jual tidak boleh negatif.',
            'jumlahStok.min' => 'Stok tidak boleh negatif.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = [
            'name' => $this->nama,
            'slug' => Str::slug($this->nama),
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'category_id' => $this->idKategori,
            'supplier_id' => $this->idPemasok ?: null,
            'buy_price' => $this->hargaBeli,
            'sell_price' => $this->hargaJual,
            'stock_quantity' => $this->jumlahStok,
            'min_stock_alert' => $this->peringatanStokMin,
            'description' => $this->deskripsi,
            'is_active' => true,
        ];

        if ($this->gambar) {
            $data['image_path'] = $this->gambar->store('produk', 'public');
        }

        try {
            if ($this->produk) {
                $this->produk->update($data);
                $pesan = 'Produk berhasil diperbarui!';
            } else {
                Product::create($data);
                $pesan = 'Produk baru berhasil ditambahkan!';
            }

            // Notifikasi CRUD
            $this->dispatch('notify', message: $pesan, type: 'success');

            return redirect()->route('admin.produk.indeks');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal menyimpan produk: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.products.form', [
            'daftarKategori' => Category::all(),
            'daftarPemasok' => Supplier::all(),
        ]);
    }
}
