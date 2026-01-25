<?php

namespace App\Livewire\Pelanggan;

use App\Models\PointHistory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Profil Pelanggan - CRM 360')]
class DetailPelanggan extends Component
{
    public $pelanggan;

    public $tabAktif = 'ringkasan'; // ringkasan, pesanan, servis, garansi, poin

    // Ubah Profil
    public $catatan;

    // Penyesuaian Poin Manual
    public $penyesuaianPoin = 0;

    public $alasanPoin = '';

    public function mount($id)
    {
        $this->pelanggan = User::with(['orders', 'serviceTickets', 'rmas', 'pointHistories'])->findOrFail($id);
        $this->catatan = $this->pelanggan->notes;
    }

    public function perbaruiCatatan()
    {
        $this->pelanggan->update(['notes' => $this->catatan]);
        $this->dispatch('notify', message: 'Catatan CRM diperbarui.', type: 'success');
    }

    public function sesuaikanPoin()
    {
        $this->validate([
            'penyesuaianPoin' => 'required|integer|not_in:0',
            'alasanPoin' => 'required|string|min:3',
        ], [
            'penyesuaianPoin.required' => 'Jumlah penyesuaian wajib diisi.',
            'alasanPoin.required' => 'Alasan wajib diisi.',
        ]);

        $saldoBaru = $this->pelanggan->loyalty_points + $this->penyesuaianPoin;

        if ($saldoBaru < 0) {
            $this->addError('penyesuaianPoin', 'Poin tidak boleh bernilai negatif.');

            return;
        }

        // 1. Perbarui User
        $this->pelanggan->loyalty_points = $saldoBaru;
        $this->pelanggan->save();

        // 2. Catat Riwayat
        PointHistory::create([
            'user_id' => $this->pelanggan->id,
            'amount' => $this->penyesuaianPoin,
            'type' => 'adjustment',
            'description' => 'Penyesuaian Manual: '.$this->alasanPoin,
        ]);

        // 3. Periksa Kenaikan Tier
        $this->periksaKenaikanTier();

        $this->dispatch('notify', message: 'Poin berhasil disesuaikan.', type: 'success');
        $this->reset(['penyesuaianPoin', 'alasanPoin']);
    }

    public function periksaKenaikanTier()
    {
        $pengeluaran = $this->pelanggan->total_spent;

        $tierBaru = 'perunggu';
        if ($pengeluaran >= 50000000) {
            $tierBaru = 'platinum';
        } elseif ($pengeluaran >= 20000000) {
            $tierBaru = 'emas';
        } elseif ($pengeluaran >= 5000000) {
            $tierBaru = 'perak';
        }

        if ($tierBaru !== $this->pelanggan->loyalty_tier) {
            $this->pelanggan->update(['loyalty_tier' => $tierBaru]);
        }
    }

    public function render()
    {
        return view('livewire.pelanggan.detail-pelanggan');
    }
}
