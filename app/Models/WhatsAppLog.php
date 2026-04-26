<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'enquiry_id',
        'phone',
        'message',
        'status',
        'error_message',
        'trigger_type'
    ];

    public function enquiry()
    {
        return $this->belongsTo(\App\Models\Enquiry::class);
    }
}
