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
        'status',
    ];

    public function alumniKuliah()
    {
        return $this->hasMany(AlumniKuliah::class);
    }
}
