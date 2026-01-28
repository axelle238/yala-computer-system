<?php

namespace App\Livewire\Reports;

use App\Models\Order;
use App\Models\CashTransaction;
use App\Models\InventoryTransaction;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Laporan Harian - Yala Computer')]
class LaporanHarian extends Component
{
    public $tanggal;

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
    }

    public function render()
    {
        $penjualan = Order::whereDate('created_at', $this->tanggal)
            ->where('status', 'completed')
            ->sum('total_amount');

        $transaksiKas = CashTransaction::whereDate('created_at', $this->tanggal)
            ->selectRaw('type, sum(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $stokKeluar = InventoryTransaction::whereDate('created_at', $this->tanggal)
            ->where('type', 'out')
            ->count();

        return view('livewire.reports.laporan-harian', [
            'ringkasan' => [
                'penjualan' => $penjualan,
                'kas_masuk' => $transaksiKas['in'] ?? 0,
                'kas_keluar' => $transaksiKas['out'] ?? 0,
                'stok_keluar' => $stokKeluar,
            ]
        ]);
    }
}
