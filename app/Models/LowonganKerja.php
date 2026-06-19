<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganKerja extends Model
{
    use HasFactory;

    protected $table = 'lowongan_kerjas';

    protected $fillable = [
        'judul',
        'perusahaan_mitra_id',
        'perusahaan_nama',
        'posisi',
        'persyaratan',
        'deskripsi',
        'gaji',
        'tanggal_tutup',
        'status',
    ];

    protected $casts = [
        'tanggal_tutup' => 'date',
    ];

    /**
     * Relasi ke PerusahaanMitra.
     */
    public function perusahaanMitra()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'perusahaan_mitra_id');
    }

    /**
     * Dapatkan nama perusahaan secara dinamis.
     */
    public function getNamaPerusahaanAttribute(): string
    {
        return $this->perusahaanMitra ? $this->perusahaanMitra->nama_perusahaan : ($this->perusahaan_nama ?? 'Lainnya');
    }
}
