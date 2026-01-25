<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
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

    /**
     * Model Produk (jika mode edit).
     */
    public ?Product $produk = null;

    // Properti Formulir
    public $nama = '';

    public $sku = '';

    public $barcode = '';

    public $idKategori = '';

    public $idPemasok = '';

    public $hargaBeli = 0;

    public $hargaJual = 0;

    public $jumlahStok = 0;

    public $peringatanStokMin = 5;

    public $deskripsi = '';

    public $gambar; // Unggahan sementara

    public $pathGambar; // Path yang sudah ada

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

            return redirect()->route('products.index');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal menyimpan produk: ' . $e->getMessage(), type: 'error');
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
