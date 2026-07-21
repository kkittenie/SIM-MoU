<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'lowongan_kerja_id',
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'tahun_lulus',
        'program_keahlian',
        'surat_lamaran',
        'file_cv',
        'status',
    ];

    /**
     * Relasi ke Lowongan Kerja
     */
    public function lowonganKerja()
    {
        return $this->belongsTo(LowonganKerja::class, 'lowongan_kerja_id');
    }
}
