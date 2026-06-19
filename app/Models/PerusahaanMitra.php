<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanMitra extends Model
{
    use HasFactory;

    protected $table = 'perusahaan_mitras';

    protected $fillable = [
        'nama_perusahaan',
        'bidang_industri',
        'alamat',
        'email',
        'nomor_telepon',
        'pic',
        'website',
        'deskripsi',
        'status_aktif',
    ];

    /**
     * Relasi ke AlumniBekerja.
     */
    public function alumniBekerja()
    {
        return $this->hasMany(AlumniBekerja::class, 'perusahaan_mitra_id');
    }

    /**
     * Relasi ke LowonganKerja.
     */
    public function lowonganKerja()
    {
        return $this->hasMany(LowonganKerja::class, 'perusahaan_mitra_id');
    }
}
