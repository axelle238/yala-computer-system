<?php

namespace App\Livewire\Pemasaran\Voucher;

use App\Models\Voucher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Form Voucher - Yala Computer')]
class Form extends Component
{
    public $kode;

    public $nama;

    public $deskripsi;

    public $tipe = 'fixed'; // fixed, percent

    public $jumlah = 0;

    public $minBelanja = 0;

    public $maksDiskon = 0;

    public $batasPenggunaan = null;

    public $batasPerUser = 1;

    public $tanggalMulai;

    public $tanggalSelesai;

    public $aktif = true;

    public function simpan()
    {
        $this->validate([
            'kode' => 'required|unique:vouchers,code|alpha_dash|uppercase',
            'nama' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'minBelanja' => 'required|numeric|min:0',
            'tanggalMulai' => 'nullable|date',
            'tanggalSelesai' => 'nullable|date|after_or_equal:tanggalMulai',
        ]);

        Voucher::create([
            'code' => $this->kode,
            'name' => $this->nama,
            'description' => $this->deskripsi,
            'type' => $this->tipe,
            'amount' => $this->jumlah,
            'min_spend' => $this->minBelanja,
            'max_discount' => $this->maksDiskon ?: null,
            'usage_limit' => $this->batasPenggunaan ?: null,
            'usage_per_user' => $this->batasPerUser,
            'start_date' => $this->tanggalMulai,
            'end_date' => $this->tanggalSelesai,
            'is_active' => $this->aktif,
        ]);

        $this->dispatch('notify', message: 'Voucher berhasil dibuat!', type: 'success');

        return redirect()->route('admin.pemasaran.voucher.indeks');
    }

    public function render()
    {
        return view('livewire.pemasaran.voucher.form');
    }
}
