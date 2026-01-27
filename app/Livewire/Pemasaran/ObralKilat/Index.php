<?php

namespace App\Livewire\Pemasaran\ObralKilat;

use App\Models\FlashSale;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Obral Kilat - Yala Computer')]
class Index extends Component
{
    use WithPagination;

    public $tampilkanForm = false;

    public $cariProduk = '';

    public $hasilPencarianProduk = [];

    // Form
    public $idObralKilat;

    public $idProduk;

    public $hargaDiskon;

    public $kuota;

    public $waktuMulai;

    public $waktuSelesai;

    public $aktif = true;

    public $namaProdukTerpilih = '';

    public function updatedCariProduk()
    {
        if (strlen($this->cariProduk) > 2) {
            $this->hasilPencarianProduk = Product::where('name', 'like', '%'.$this->cariProduk.'%')
                ->limit(5)->get();
        } else {
            $this->hasilPencarianProduk = [];
        }
    }

    public function pilihProduk($id, $nama)
    {
        $this->idProduk = $id;
        $this->namaProdukTerpilih = $nama;
        $this->hasilPencarianProduk = [];
        $this->cariProduk = '';
    }

    public function buat()
    {
        $this->reset(['idObralKilat', 'idProduk', 'hargaDiskon', 'kuota', 'waktuMulai', 'waktuSelesai', 'aktif', 'namaProdukTerpilih']);
        $this->tampilkanForm = true;
    }

    public function simpan()
    {
        $this->validate([
            'idProduk' => 'required|exists:products,id',
            'hargaDiskon' => 'required|numeric|min:1',
            'kuota' => 'required|numeric|min:1',
            'waktuMulai' => 'required|date',
            'waktuSelesai' => 'required|date|after:waktuMulai',
        ]);

        FlashSale::updateOrCreate(['id' => $this->idObralKilat], [
            'product_id' => $this->idProduk,
            'discount_price' => $this->hargaDiskon,
            'quota' => $this->kuota,
            'start_time' => $this->waktuMulai,
            'end_time' => $this->waktuSelesai,
            'is_active' => $this->aktif,
        ]);

        $this->tampilkanForm = false;
        $this->dispatch('notify', message: 'Obral Kilat tersimpan.', type: 'success');
    }

    public function hapus($id)
    {
        FlashSale::destroy($id);
        $this->dispatch('notify', message: 'Obral Kilat dihapus.', type: 'success');
    }

    public function render()
    {
        $obral = FlashSale::with('product')->latest()->paginate(10);

        return view('livewire.pemasaran.obral-kilat.index', ['obral' => $obral]);
    }
}
