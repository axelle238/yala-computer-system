<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Model Pesanan (Order)
 */
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_whatsapp',
        'order_number',
        'total_amount',
        'status',
        'payment_status', // unpaid, partial, paid
        'notes',
        'shipping_address',
        'shipping_city',
        'shipping_courier',
        'shipping_cost',
        'shipping_tracking_number',
        'shipped_at',
        'points_redeemed',
        'discount_amount',
        'due_date',
        'voucher_code', // Tambahan dari migrasi
        'voucher_discount', // Tambahan dari migrasi
        'snap_token', // Midtrans
        'payment_url', // Midtrans
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relasi ke Item Pesanan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Pengguna (Pelanggan).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Pembayaran.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Relasi ke Penggunaan Voucher.
     */
    public function penggunaanVoucher(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    // Alias untuk kompatibilitas sementara atau perbaikan pemanggilan
    public function voucherUsages()
    {
        return $this->penggunaanVoucher();
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
