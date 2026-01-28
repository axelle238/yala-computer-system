<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Detail Log Aktivitas - Yala Computer')]
class Show extends Component
{
    public $log;

    public function mount($id)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }
        $this->log = ActivityLog::with('user')->findOrFail($id);
    }

    public function readableKey($key)
    {
        $map = [
            'id' => 'ID Data',
            'created_at' => 'Tanggal Dibuat',
            'updated_at' => 'Tanggal Diperbarui',
            'deleted_at' => 'Tanggal Dihapus',
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Kata Sandi (Enkripsi)',
            'phone' => 'Nomor Telepon',
            'address' => 'Alamat',
            'role' => 'Peran / Jabatan',
            'status' => 'Status',
            'price' => 'Harga',
            'quantity' => 'Jumlah (Qty)',
            'total_amount' => 'Total Nominal',
            'description' => 'Deskripsi',
            'title' => 'Judul',
            'content' => 'Konten',
            'is_active' => 'Status Aktif',
            'stock' => 'Stok',
            'sku' => 'Kode SKU',
            'category_id' => 'ID Kategori',
            'user_id' => 'ID Pengguna',
            'order_id' => 'ID Pesanan',
            'payment_status' => 'Status Pembayaran',
            'shipping_status' => 'Status Pengiriman',
            'notes' => 'Catatan',
            'tax_rate' => 'Tarif Pajak',
            'service_charge' => 'Biaya Layanan',
            'store_name' => 'Nama Toko',
            'store_address' => 'Alamat Toko',
            'store_phone' => 'Telepon Toko',
            'store_email' => 'Email Toko',
        ];

        return $map[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    public function formatValue($key, $value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if (empty($value)) {
            return '-';
        }

        if (in_array($key, ['created_at', 'updated_at', 'deleted_at', 'date', 'datetime'])) {
            try {
                return \Carbon\Carbon::parse($value)->locale('id')->isoFormat('D MMMM Y HH:mm');
            } catch (\Exception $e) {
                return $value;
            }
        }

        if (in_array($key, ['price', 'total_amount', 'amount', 'salary', 'tax', 'discount', 'subtotal', 'grand_total', 'cost'])) {
            return 'Rp '.number_format((float) $value, 0, ',', '.');
        }

        if (in_array($key, ['is_active', 'is_published', 'status_active'])) {
            return $value ? 'Aktif / Ya' : 'Tidak Aktif / Tidak';
        }

        if ($key === 'password') {
            return '******** (Disembunyikan)';
        }

        return $value;
    }

    public function render()
    {
        return view('livewire.activity-logs.show');
    }
}
