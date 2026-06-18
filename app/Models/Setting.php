<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'fonnte_token',
        'whatsapp_target',
        'whatsapp_active',
    ];

    protected $casts = [
        'whatsapp_active' => 'boolean',
    ];

    /**
     * Get the single settings record (id = 1), creating it if it doesn't exist.
     */
    public static function getSettings()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'fonnte_token' => null,
                'whatsapp_target' => null,
                'whatsapp_active' => false,
            ]
        );
    }
}
