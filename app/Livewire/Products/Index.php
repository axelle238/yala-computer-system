<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Manajemen Produk - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $cari = '';

    #[Url(history: true)]
    public $filterKategori = '';

    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';

    // Reset pagination saat melakukan pencarian
    public function updatedCari()
    {
        $this->resetPage();
    }

    public function updatedFilterKategori()
    {
        $this->resetPage();
    }

    public function hapus($id)
    {
        $produk = Product::find($id);
        if ($produk) {
            $produk->delete();
            $this->dispatch('notify', message: 'Produk berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        // Query Builder untuk Produk
        $daftarProduk = Product::query()
            ->with(['category', 'supplier']) // Eager Load untuk performa
            ->when($this->cari, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->cari . '%')
                        ->orWhere('sku', 'like', '%' . $this->cari . '%')
                        ->orWhere('barcode', 'like', '%' . $this->cari . '%');
                });
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('category_id', $this->filterKategori);
            })
            ->orderBy($this->kolomUrut, $this->arahUrut)
            ->paginate(10);

        $daftarKategori = Category::all();

        return view('livewire.products.index', [
            'daftarProduk' => $daftarProduk,
            'daftarKategori' => $daftarKategori,
        ]);
    }
}