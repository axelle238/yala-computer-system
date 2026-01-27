<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Pengguna (User)
 */
class User extends Authenticatable
{
    use HasFactory, LogsActivity, Notifiable;

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'id_peran',
        'name',
        'email',
        'phone',
        'password',
        'role', // Cadangan: admin, technician, cashier, customer
        'loyalty_points',
        'loyalty_tier',
        'total_spent',
        'last_purchase_at',
        'notes',
    ];

    /**
     * Atribut yang disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_purchase_at' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    // --- Relasi ---

    /**
     * Relasi ke Peran (Role).
     */
    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'id_peran');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function serviceTickets()
    {
        return $this->hasMany(ServiceTicket::class); // Sebagai pelanggan
    }

    public function rmas()
    {
        return $this->hasMany(Rma::class);
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    // --- Bantuan (Helpers) ---

    /**
     * Mengecek apakah pengguna memiliki hak akses tertentu.
     */
    public function punyaAkses(string $kodeAkses): bool
    {
        // 1. Pemilik/Admin Utama selalu punya akses penuh
        if ($this->isAdmin()) {
            return true;
        }

        // 2. Cek melalui sistem Peran baru
        if ($this->peran) {
            return $this->peran->punyaAkses($kodeAkses);
        }

        // 3. Cadangan (Fallback) logika lama
        $petaAksesLama = [
            'technician' => ['service.', 'workbench.'],
            'cashier' => ['pos.', 'sales.', 'finance.'],
            'customer' => ['order.'],
        ];

        if (isset($petaAksesLama[$this->role])) {
            foreach ($petaAksesLama[$this->role] as $prefiks) {
                if (str_starts_with($kodeAkses, $prefiks)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Mengecek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Mengecek apakah user memiliki salah satu role yang diberikan.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Mengecek apakah user memiliki role tertentu.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $role = strtolower($role);
        
        // 1. Cek kolom 'role' legacy
        if (strtolower($this->role ?? '') === $role) {
            return true;
        }

        // 2. Cek relasi 'peran'
        $namaPeran = $this->peran ? strtolower($this->peran->nama) : '';
        if ($namaPeran === $role) {
            return true;
        }

        // 3. Mapping Bahasa (Inggris <-> Indonesia)
        $petaIstilah = [
            'admin' => ['administrator'],
            'owner' => ['pemilik'],
            'technician' => ['teknisi'],
            'cashier' => ['kasir'],
            'warehouse' => ['gudang', 'logistik', 'inventory'],
            'hr' => ['hrd', 'personalia'],
            'customer' => ['pelanggan'],
        ];

        if (isset($petaIstilah[$role]) && in_array($namaPeran, $petaIstilah[$role])) {
            return true;
        }

        // Cek kebalikan (jika input indonesia, cek inggris di role legacy)
        // (Sederhana saja untuk sekarang: utamakan input bahasa Inggris dari nav.php)

        return false;
    }
}
