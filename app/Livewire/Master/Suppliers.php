<?php

namespace App\Livewire\Master;

use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Master Pemasok (Supplier) - Yala Computer')]
class Suppliers extends Component
{
    use WithPagination;

    public $cari = '';

    public $tampilkanForm = false;

    // Properti Form
    public $idPemasok;

    public $nama;

    public $surel;

    public $telepon;

    public $alamat;

    public function buat()
    {
        $this->reset(['idPemasok', 'nama', 'surel', 'telepon', 'alamat']);
        $this->tampilkanForm = true;
    }

    public function ubah($id)
    {
        $pemasok = Supplier::findOrFail($id);
        $this->idPemasok = $pemasok->id;
        $this->nama = $pemasok->name;
        $this->surel = $pemasok->email;
        $this->telepon = $pemasok->phone;
        $this->alamat = $pemasok->address;
        $this->tampilkanForm = true;
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'surel' => 'nullable|email',
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
        ], [
            'nama.required' => 'Nama pemasok wajib diisi.',
            'surel.email' => 'Format surel tidak valid.',
        ]);

        Supplier::updateOrCreate(
            ['id' => $this->idPemasok],
            [
                'name' => $this->nama,
                'email' => $this->surel,
                'phone' => $this->telepon,
                'address' => $this->alamat,
            ]
        );

        $this->tampilkanForm = false;
        $this->dispatch('notify', message: 'Data pemasok berhasil disimpan.', type: 'success');
    }

    public function hapus($id)
    {
        Supplier::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Pemasok telah dihapus.', type: 'success');
    }

    public function render()
    {
        $daftarPemasok = Supplier::where('name', 'like', '%'.$this->cari.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.suppliers', ['daftarPemasok' => $daftarPemasok]);
    }
}
