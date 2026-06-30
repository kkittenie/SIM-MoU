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
        'tahun_ajaran_aktif',
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
                'tahun_ajaran_aktif' => date('Y'),
            ]
        );
    }

    /**
     * Get the active academic year (either from session or fallback to settings).
     */
    public static function getActiveTahunAjaran()
    {
        if (session()->has('selected_tahun_ajaran')) {
            return session('selected_tahun_ajaran');
        }
        return self::getSettings()->tahun_ajaran_aktif ?: date('Y');
    }
}
