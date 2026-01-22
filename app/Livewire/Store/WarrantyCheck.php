<?php

namespace App\Livewire\Store;

use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.store')]
#[Title('Cek Garansi - Yala Computer')]
class WarrantyCheck extends Component
{
    public $serial_number = '';
    public $result = null;

    public function check()
    {
        $this->validate(['serial_number' => 'required']);

        // Cari transaksi keluar yang mengandung SN ini
        $transaction = InventoryTransaction::where('type', 'out')
            ->where('serial_numbers', 'like', '%' . $this->serial_number . '%')
            ->with('product')
            ->latest()
            ->first();

        if ($transaction) {
            $purchaseDate = $transaction->created_at;
            $duration = $transaction->product->warranty_duration; // Bulan
            $expiryDate = $purchaseDate->copy()->addMonths($duration);
            
            $this->result = [
                'product' => $transaction->product->name,
                'purchase_date' => $purchaseDate->format('d M Y'),
                'expiry_date' => $expiryDate->format('d M Y'),
                'status' => now() <= $expiryDate ? 'valid' : 'expired',
                'days_left' => now() <= $expiryDate ? now()->diffInDays($expiryDate) : 0,
            ];
        } else {
            $this->addError('serial_number', 'Serial Number tidak ditemukan dalam database penjualan kami.');
            $this->result = null;
        }
    }

    public function render()
    {
        return view('livewire.store.warranty-check');
    }
}
