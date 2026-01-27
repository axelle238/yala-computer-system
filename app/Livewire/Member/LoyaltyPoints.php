<?php

namespace App\Livewire\Member;

use App\Models\PointHistory;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.store')] // Layout khusus Storefront/Member Area
#[Title('Poin Loyalitas Saya - Yala Computer')]
class LoyaltyPoints extends Component
{
    use WithPagination;

    public $filterRiwayat = 'semua'; // semua, masuk, keluar

    public function tukarVoucher($voucherId)
    {
        $voucher = Voucher::find($voucherId);
        $user = Auth::user();

        if (! $voucher || ! $voucher->is_public || $voucher->points_cost <= 0) {
            $this->dispatch('notify', message: 'Voucher tidak valid untuk ditukar.', type: 'error');

            return;
        }

        if ($user->points < $voucher->points_cost) {
            $this->dispatch('notify', message: 'Poin Anda tidak mencukupi.', type: 'error');

            return;
        }

        // Cek limit penukaran jika perlu (misal: 1 user max 1 voucher tipe X)
        // ... (Logika sederhana dulu)

        DB::transaction(function () use ($user, $voucher) {
            // 1. Kurangi Poin User
            $user->decrement('points', $voucher->points_cost);

            // 2. Catat Riwayat Poin
            PointHistory::create([
                'user_id' => $user->id,
                'type' => 'redeemed',
                'amount' => -$voucher->points_cost,
                'description' => "Penukaran Voucher: {$voucher->code}",
                'reference_type' => Voucher::class,
                'reference_id' => $voucher->id,
            ]);

            // 3. Berikan Voucher ke User (VoucherUsage record)
            VoucherUsage::create([
                'voucher_id' => $voucher->id,
                'user_id' => $user->id,
                'used_at' => null, // Belum dipakai
                // Tambahan field jika perlu kode unik per user
            ]);
        });

        $this->dispatch('notify', message: "Berhasil menukar {$voucher->points_cost} poin dengan voucher!", type: 'success');
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Katalog Hadiah (Voucher Publik)
        $katalogHadiah = Voucher::where('is_public', true)
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->where('points_cost', '>', 0)
            ->get();

        // 2. Riwayat Poin
        $riwayat = PointHistory::where('user_id', $user->id)
            ->when($this->filterRiwayat == 'masuk', fn ($q) => $q->where('amount', '>', 0))
            ->when($this->filterRiwayat == 'keluar', fn ($q) => $q->where('amount', '<', 0))
            ->latest()
            ->paginate(10);

        return view('livewire.member.loyalty-points', [
            'poinSaatIni' => $user->points,
            'katalogHadiah' => $katalogHadiah,
            'riwayatPoin' => $riwayat,
        ]);
    }
}
