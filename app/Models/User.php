<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
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
        'role_v2_id', // New RBAC
        'access_rights',
        'base_salary',
        'join_date',
        'points',
        'referral_code',
        'referrer_id',
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

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    // --- Helper Methods untuk Role & Permission ---

    public function roleV2()
    {
        return $this->belongsTo(Role::class, 'role_v2_id');
    }

    public function hasPermissionTo($permission)
    {
        // 1. Super Admin Bypass
        if ($this->isAdmin() || $this->roleV2?->slug === 'super-admin') {
            return true;
        }

        // 2. Check Database Permissions (RBAC V2)
        if ($this->roleV2) {
            return $this->roleV2->permissions->contains('name', $permission);
        }

        // 3. Fallback to Legacy Enum/Array (RBAC V1)
        if ($this->isAdmin()) return true;
        if ($this->isOwner()) return in_array($permission, ['view_dashboard', 'view_reports']);
        
        return in_array($permission, $this->access_rights ?? []);
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->roleV2?->slug === 'admin';
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

    // --- CRM & Loyalty ---

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function interactions()
    {
        return $this->hasMany(CustomerInteraction::class, 'user_id');
    }

    public function loyaltyLogs()
    {
        return $this->hasMany(LoyaltyLog::class);
    }

    public function recalculateLevel()
    {
        $totalSpent = $this->orders()->where('status', 'completed')->sum('total_amount');
        $this->update(['lifetime_value' => $totalSpent]);

        $group = CustomerGroup::where('min_spend', '<=', $totalSpent)
            ->orderByDesc('min_spend')
            ->first();

        if ($group && $this->customer_group_id !== $group->id) {
            $this->update(['customer_group_id' => $group->id]);
        }
    }

    // --- Referral System ---

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    // --- Gamification Logic ---

    public function getTotalSpentAttribute()
    {
        // Cache or calculate on fly. For now, on fly.
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    public function getLevelAttribute()
    {
        $spent = $this->total_spent;

        if ($spent > 50000000) return ['name' => 'Legend', 'color' => 'text-amber-400', 'bg' => 'bg-amber-400/10', 'icon' => 'crown'];
        if ($spent > 20000000) return ['name' => 'Pro', 'color' => 'text-purple-400', 'bg' => 'bg-purple-400/10', 'icon' => 'lightning-bolt'];
        if ($spent > 5000000)  return ['name' => 'Enthusiast', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-400/10', 'icon' => 'star'];
        
        return ['name' => 'Newbie', 'color' => 'text-slate-400', 'bg' => 'bg-slate-400/10', 'icon' => 'user'];
    }

    public function getNextLevelProgressAttribute()
    {
        $spent = $this->total_spent;
        $levels = [
            5000000 => 5000000,
            20000000 => 20000000,
            50000000 => 50000000,
        ];

        foreach ($levels as $limit => $target) {
            if ($spent < $limit) {
                $prevLimit = $limit === 5000000 ? 0 : ($limit === 20000000 ? 5000000 : 20000000);
                $progress = ($spent - $prevLimit) / ($target - $prevLimit) * 100;
                return [
                    'percent' => min(100, max(0, $progress)),
                    'target' => $target,
                    'remaining' => $target - $spent
                ];
            }
        }

        return ['percent' => 100, 'target' => 0, 'remaining' => 0]; // Max level
    }
}