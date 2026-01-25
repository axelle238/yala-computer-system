<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBlast extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'campaign_name',
        'message_template',
        'target_audience',
        'scheduled_at',
        'status',
        'total_recipients',
        'success_count',
        'failed_count',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}