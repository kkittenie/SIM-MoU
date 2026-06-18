<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'kerja_sama_id',
        'type',
        'recipient',
        'message',
        'status',
        'response',
    ];

    /**
     * Get the KerjaSama partnership associated with this WhatsApp log.
     */
    public function kerjaSama()
    {
        return $this->belongsTo(KerjaSama::class, 'kerja_sama_id');
    }
}
