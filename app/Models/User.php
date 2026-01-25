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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // admin, technician, cashier, customer
        'loyalty_points',
        'loyalty_tier',
        'total_spent',
        'last_purchase_at',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_purchase_at' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    // --- Relationships ---

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function serviceTickets()
    {
        return $this->hasMany(ServiceTicket::class); // As customer
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

    // --- Helpers ---

    public function getTierColorAttribute()
    {
        return match($this->loyalty_tier) {
            'bronze' => 'text-amber-700 bg-amber-100 border-amber-200',
            'silver' => 'text-slate-700 bg-slate-200 border-slate-300',
            'gold' => 'text-yellow-700 bg-yellow-100 border-yellow-300',
            'platinum' => 'text-indigo-700 bg-indigo-100 border-indigo-300',
            default => 'text-slate-600 bg-slate-100',
        };
    }

    /**
     * Check if user has a specific permission.
     * Temporary implementation using 'role' column.
     */
    public function hasPermissionTo($permission)
    {
        // 1. Admin always true
        if ($this->role === 'admin') {
            return true;
        }

        // 2. Simple Role-Based Permission Mapping
        $rolePermissions = [
            'technician' => ['service.', 'workbench.'],
            'cashier' => ['pos.', 'sales.', 'finance.'],
            'customer' => ['order.'],
        ];

        if (isset($rolePermissions[$this->role])) {
            foreach ($rolePermissions[$this->role] as $prefix) {
                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }
}