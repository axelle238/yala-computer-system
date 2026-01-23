<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

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
        'base_salary',
        'join_date',
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
            'access_rights' => 'array',
            'join_date' => 'date',
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
        return !in_array($this->role, ['admin', 'owner']);
    }

    /**
     * Cek apakah user memiliki izin spesifik.
     */
    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isOwner()) {
            $ownerPermissions = ['view_dashboard', 'view_reports', 'view_products'];
            return in_array($permission, $ownerPermissions);
        }

        if ($this->isEmployee()) {
            return in_array($permission, $this->access_rights ?? []);
        }

        return false;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function savedBuilds()
    {
        return $this->hasMany(SavedBuild::class);
    }
}