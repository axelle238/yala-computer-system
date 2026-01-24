<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTicketProgress extends Model
{
    protected $table = 'service_ticket_progress';
    protected $guarded = [];
    protected $casts = [
        'attachment_paths' => 'array',
        'is_public' => 'boolean',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}