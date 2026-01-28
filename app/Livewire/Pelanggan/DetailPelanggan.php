<?php

namespace App\Livewire\Pelanggan;

use App\Models\PointHistory;
use App\Models\User;
use App\Services\YalaIntelligence;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Profil Pelanggan - CRM 360')]
class DetailPelanggan extends Component
{
    public $pelanggan;

    // Navigasi Tab
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

    public function gantiTab($tab)
    {
        $this->tabAktif = $tab;
    }

    public function perbaruiCatatan()
    {
        $this->pelanggan->update(['notes' => $this->catatan]);
        $this->dispatch('notify', message: 'Catatan internal pelanggan berhasil diperbarui.', type: 'success');
    }

    public function sesuaikanPoin()
    {
        // Hanya Admin/Owner yang boleh ubah poin manual
        if (! Auth::user()->isAdmin() && ! Auth::user()->isOwner()) {
            return;
        }

        $this->validate([
            'penyesuaianPoin' => 'required|integer|not_in:0',
            'alasanPoin' => 'required|string|min:3',
        ], [
            'penyesuaianPoin.required' => 'Jumlah penyesuaian wajib diisi.',
            'alasanPoin.required' => 'Alasan wajib diisi.',
        ]);

        $saldoBaru = $this->pelanggan->points + $this->penyesuaianPoin; // Gunakan 'points' bukan 'loyalty_points' sesuai migrasi

        if ($saldoBaru < 0) {
            $this->addError('penyesuaianPoin', 'Saldo poin tidak boleh menjadi negatif.');

            return;
        }

        // 1. Perbarui User
        $this->pelanggan->points = $saldoBaru;
        $this->pelanggan->save();

        // 2. Catat Riwayat
        PointHistory::create([
            'user_id' => $this->pelanggan->id,
            'amount' => $this->penyesuaianPoin,
            'type' => 'adjusted',
            'description' => 'Penyesuaian Manual: '.$this->alasanPoin,
        ]);

        // 3. Periksa Kenaikan Tier
        $this->periksaKenaikanTier();

        $this->dispatch('notify', message: 'Poin berhasil disesuaikan.', type: 'success');
        $this->reset(['penyesuaianPoin', 'alasanPoin']);
    }

    public function periksaKenaikanTier()
    {
        // Asumsi kolom total_spent ada di User (dari order aggregation)
        $pengeluaran = $this->pelanggan->orders()->where('status', 'completed')->sum('total_amount');

        $tierBaru = 'Bronze';
        if ($pengeluaran >= 50000000) {
            $tierBaru = 'Platinum';
        } elseif ($pengeluaran >= 20000000) {
            $tierBaru = 'Gold';
        } elseif ($pengeluaran >= 5000000) {
            $tierBaru = 'Silver';
        }

        if ($tierBaru !== $this->pelanggan->loyalty_tier) {
            $this->pelanggan->update(['loyalty_tier' => $tierBaru]);
        }
    }

    public function render(YalaIntelligence $ai)
    {
        // Analisis Churn
        $lastOrder = $this->pelanggan->orders()->latest()->first();
        $hariSejak = $lastOrder ? $lastOrder->created_at->diffInDays(now()) : 999;
        $totalBelanja = $this->pelanggan->orders()->sum('total_amount');
        $frekuensi = $this->pelanggan->orders()->count();

        $churnAnalysis = $ai->analisisChurnPelanggan($frekuensi, $hariSejak, $totalBelanja);

        return view('livewire.pelanggan.detail-pelanggan', [
            'churnAnalysis' => $churnAnalysis
        ]);
    }
}
