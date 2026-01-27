<?php

namespace App\Livewire\Pemasaran\ObralKilat;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Tambah Obral Kilat - Yala Computer')]
class Form extends Component
{
    public $idProduk;

    public $hargaDiskon;

    public $waktuMulai;

    public $waktuSelesai;

    public $kuota = 10;

    // Search
    public $cariProduk = '';

    public $hasilPencarian = [];

    public $namaProdukTerpilih = '';

    public $hargaAsli = 0;

    public function updatedCariProduk()
    {
        if (strlen($this->cariProduk) > 2) {
            $this->hasilPencarian = Product::where('name', 'like', '%'.$this->cariProduk.'%')
                ->where('is_active', true)
                ->take(5)->get();
        } else {
            $this->hasilPencarian = [];
        }
    }

    public function pilihProduk($id)
    {
        $produk = Product::find($id);
        $this->idProduk = $produk->id;
        $this->namaProdukTerpilih = $produk->name;
        $this->hargaAsli = $produk->sell_price;
        $this->cariProduk = ''; // Close dropdown
        $this->hasilPencarian = [];
    }

    public function simpan()
    {
        $this->validate([
            'idProduk' => 'required|exists:products,id',
            'hargaDiskon' => 'required|numeric|min:1|lt:hargaAsli',
            'waktuMulai' => 'required|date',
            'waktuSelesai' => 'required|date|after:waktuMulai',
            'kuota' => 'required|integer|min:1',
        ]);

        FlashSale::create([
            'product_id' => $this->idProduk,
            'discount_price' => $this->hargaDiskon,
            'start_time' => $this->waktuMulai,
            'end_time' => $this->waktuSelesai,
            'quota' => $this->kuota,
            'is_active' => true,
        ]);

        $this->dispatch('notify', message: 'Obral Kilat berhasil dibuat!', type: 'success');

        return redirect()->route('admin.pemasaran.obral-kilat.indeks');
    }

    public function render()
    {
        return view('livewire.pemasaran.obral-kilat.form');
    }
}
