<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'access_rights',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'access_rights' => 'array', // Otomatis convert JSON ke Array
        ];
    }

    // --- Helper Methods untuk Role & Permission ---

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    /**
     * Cek apakah user memiliki izin spesifik.
     * Admin & Owner biasanya punya akses implisit, tapi kita bisa batasi Owner hanya view.
     */
    public function hasPermission($permission)
    {
        // Admin selalu punya semua akses
        if ($this->isAdmin()) {
            return true;
        }

        // Owner biasanya punya akses lihat laporan dan dashboard
        if ($this->isOwner()) {
            $ownerPermissions = ['view_dashboard', 'view_reports', 'view_products'];
            return in_array($permission, $ownerPermissions);
        }

        // Pegawai tergantung settingan manual
        if ($this->isEmployee()) {
            return in_array($permission, $this->access_rights ?? []);
        }

        return false;
    }
}