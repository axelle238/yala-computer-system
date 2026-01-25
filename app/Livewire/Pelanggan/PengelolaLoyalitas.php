<?php

namespace App\Livewire\Pelanggan;

use App\Models\CustomerGroup;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Loyalitas & Keanggotaan - Yala Computer')]
class PengelolaLoyalitas extends Component
{
    public $daftarGrup;

    public $aksiAktif = null; // null, 'formulir'

    public $nama;

    public $kode;

    public $minBelanja;

    public $persenDiskon;

    public $warna = 'gray';

    public $idGrupDiubah;

    public function mount()
    {
        $this->muatGrup();
    }

    public function muatGrup()
    {
        $this->daftarGrup = CustomerGroup::orderBy('min_spend')->get();
    }

    public function bukaPanelFormulir($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $grup = CustomerGroup::findOrFail($id);
            $this->idGrupDiubah = $id;
            $this->nama = $grup->name;
            $this->kode = $grup->code;
            $this->minBelanja = $grup->min_spend;
            $this->persenDiskon = $grup->discount_percent;
            $this->warna = $grup->color;
        } else {
            $this->reset(['nama', 'kode', 'minBelanja', 'persenDiskon', 'warna', 'idGrupDiubah']);
        }
        $this->aksiAktif = 'formulir';
    }

    public function tutupPanel()
    {
        $this->aksiAktif = null;
        $this->reset(['nama', 'kode', 'minBelanja', 'persenDiskon', 'warna', 'idGrupDiubah']);
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required|string',
            'kode' => 'required|string|unique:customer_groups,code,'.$this->idGrupDiubah,
            'minBelanja' => 'required|numeric',
            'persenDiskon' => 'required|numeric|max:100',
        ], [
            'nama.required' => 'Nama level wajib diisi.',
            'kode.required' => 'Kode unik wajib diisi.',
            'persenDiskon.max' => 'Diskon maksimal 100%.',
        ]);

        $data = [
            'name' => $this->nama,
            'code' => $this->kode,
            'min_spend' => $this->minBelanja,
            'discount_percent' => $this->persenDiskon,
            'color' => $this->warna,
        ];

        if ($this->idGrupDiubah) {
            CustomerGroup::find($this->idGrupDiubah)->update($data);
        } else {
            CustomerGroup::create($data);
        }

        $this->tutupPanel();
        $this->muatGrup();
        $this->dispatch('notify', message: 'Level membership berhasil disimpan.', type: 'success');
    }

    public function hapus($id)
    {
        try {
            CustomerGroup::findOrFail($id)->delete();
            $this->muatGrup();
            $this->dispatch('notify', message: 'Level berhasil dihapus.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal menghapus (Masih ada anggota).', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pelanggan.pengelola-loyalitas');
    }
}
