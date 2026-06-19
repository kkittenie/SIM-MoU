<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumniBekerja extends Model
{
    use HasFactory;

    protected $table = 'alumni_bekerjas';

    protected $fillable = [
        'nama_alumni',
        'perusahaan_mitra_id',
        'perusahaan_nama',
        'jabatan',
        'tanggal_masuk',
        'tahun_lulus',
        'bidang_industri',
        'gaji',
        'status_pekerjaan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * Relasi ke PerusahaanMitra.
     */
    public function perusahaanMitra()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'perusahaan_mitra_id');
    }

    /**
     * Dapatkan nama perusahaan secara dinamis (dari Mitra atau fallback manual).
     */
    public function getNamaPerusahaanAttribute(): string
    {
        return $this->perusahaanMitra ? $this->perusahaanMitra->nama_perusahaan : ($this->perusahaan_nama ?? 'Lainnya');
    }
}
