<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Menghasilkan deskripsi naratif yang mudah dibaca.
     */
    public function generateNarrative(): string
    {
        // Prioritaskan deskripsi manual jika ada
        if (! empty($this->description)) {
            return $this->description;
        }

        $actor = $this->user ? $this->user->name : 'Sistem';
        $modelName = $this->translateModel(class_basename($this->model_type));

        switch ($this->action) {
            case 'create':
                return "{$actor} menambahkan data {$modelName} baru.";

            case 'update':
                $changes = [];
                if (isset($this->properties['old']) && isset($this->properties['new'])) {
                    foreach ($this->properties['new'] as $key => $newValue) {
                        $oldValue = $this->properties['old'][$key] ?? '-';
                        if ($oldValue != $newValue) {
                            $changes[] = "{$key} dari '{$oldValue}' menjadi '{$newValue}'";
                        }
                    }
                }
                $changeStr = ! empty($changes) ? implode(', ', $changes) : 'data';

                return "{$actor} memperbarui {$modelName} (ID: {$this->model_id}): mengubah {$changeStr}.";

            case 'delete':
                return "{$actor} menghapus data {$modelName} (ID: {$this->model_id}).";

            case 'login':
                return "{$actor} berhasil masuk ke sistem.";

            case 'logout':
                return "{$actor} keluar dari sistem.";

            default:
                return "{$actor} melakukan aksi {$this->action} pada {$modelName}.";
        }
    }

    private function translateModel(string $model): string
    {
        return match ($model) {
            'User' => 'Pengguna',
            'Product' => 'Produk',
            'Order' => 'Pesanan',
            'Customer' => 'Pelanggan',
            'Supplier' => 'Pemasok',
            'Employee' => 'Karyawan',
            'ServiceTicket' => 'Tiket Servis',
            'Transaction' => 'Transaksi',
            'Expense' => 'Pengeluaran',
            'PurchaseOrder' => 'Pesanan Pembelian',
            default => $model,
        };
    }
}
