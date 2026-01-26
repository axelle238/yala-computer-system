<?php

namespace App\Livewire\Pemasaran\Voucher;

use App\Models\Voucher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Voucher & Promo')]
class Index extends Component
{
    use WithPagination;

    public $pencarian = '';

    public $tampilkanForm = false;

    // Form Inputs
    public $idVoucher;

    public $kode;

    public $tipe = 'fixed';

    public $jumlah;

    public $minBelanja = 0;

    public $kuota = 100;

    public $tanggalMulai;

    public $tanggalSelesai;

    public $aktif = true;

    public function buat()
    {
        $this->reset(['idVoucher', 'kode', 'tipe', 'jumlah', 'minBelanja', 'kuota', 'tanggalMulai', 'tanggalSelesai', 'aktif']);
        $this->kode = strtoupper(\Illuminate\Support\Str::random(8));
        $this->tampilkanForm = true;
    }

    public function ubah($id)
    {
        $v = Voucher::findOrFail($id);
        $this->idVoucher = $v->id;
        $this->kode = $v->code;
        $this->tipe = $v->type;
        $this->jumlah = $v->amount;
        $this->minBelanja = $v->min_spend;
        $this->kuota = $v->quota;
        $this->tanggalMulai = $v->start_date ? $v->start_date->format('Y-m-d') : null;
        $this->tanggalSelesai = $v->end_date ? $v->end_date->format('Y-m-d') : null;
        $this->aktif = $v->is_active;
        $this->tampilkanForm = true;
    }

    public function simpan()
    {
        $this->validate([
            'kode' => 'required|unique:vouchers,code,'.$this->idVoucher,
            'tipe' => 'required|in:fixed,percentage',
            'jumlah' => 'required|numeric|min:1',
            'kuota' => 'required|numeric|min:1',
        ]);

        Voucher::updateOrCreate(['id' => $this->idVoucher], [
            'code' => strtoupper($this->kode),
            'type' => $this->tipe,
            'amount' => $this->jumlah,
            'min_spend' => $this->minBelanja,
            'quota' => $this->kuota,
            'start_date' => $this->tanggalMulai,
            'end_date' => $this->tanggalSelesai,
            'is_active' => $this->aktif,
        ]);

        $this->tampilkanForm = false;
        $this->dispatch('notify', message: 'Voucher berhasil disimpan.', type: 'success');
    }

    public function hapus($id)
    {
        Voucher::destroy($id);
        $this->dispatch('notify', message: 'Voucher dihapus.', type: 'success');
    }

    public function render()
    {
        $voucher = Voucher::where('code', 'like', '%'.$this->pencarian.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.pemasaran.voucher.index', ['voucher' => $voucher]);
    }
}