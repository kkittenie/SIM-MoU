<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Universitas extends Model
{
    protected $table = 'universitas';

    protected $fillable = [
        'nama_universitas',
        'kota',
        'provinsi',
        'website',
        'nomor_telepon',
        'jenis',
        'akreditasi',
        'lokasi_kuliah',      
        'cara_masuk',
        'status',
    ];

    /**
     * Get label untuk lokasi_kuliah
     */
    public function getLokasiKuliahLabel()
    {
        return match($this->lokasi_kuliah) {
            'dalam_negeri' => 'Dalam Negeri',
            'luar_negeri' => 'Luar Negeri',
            default => 'Unknown'
        };
    }

    /**
     * Get label untuk cara_masuk
     */
    public function getCaraMasukLabel()
    {
        return match($this->cara_masuk) {
            'snbp' => 'SNBP',
            'utbk' => 'UTBK',
            'ujian_masuk' => 'Ujian Masuk',
            'beasiswa' => 'Beasiswa',
            'transfer' => 'Transfer',
            'lainnya' => 'Lainnya',
            default => 'Unknown'
        };
    }

    public function alumniKuliah()
    {
        return $this->hasMany(AlumniKuliah::class);
    }
}
