<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'user_id',
        'kerja_sama_id',
        'type',
        'recipient',
        'message',
        'status',
        'response',
    ];

    /**
     * Get the User who sent this WhatsApp message.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the KerjaSama partnership associated with this WhatsApp log.
     */
    public function kerjaSama()
    {
        return $this->belongsTo(KerjaSama::class, 'kerja_sama_id');
    }
}
