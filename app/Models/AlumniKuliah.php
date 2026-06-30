<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByTahunAjaran;

class AlumniKuliah extends Model
{
    use HasFactory, FilterByTahunAjaran;

    protected $table = 'alumni_kuliah';

    protected $fillable = [
        'nama_alumni',
        'nis',
        'tahun_lulus',
        'universitas_id',
        'program_studi',
        'email_alumni',
        'nomor_telepon',
        'status_alumni',
    ];

    public function universitas()
    {
        return $this->belongsTo(Universitas::class);
    }

    public function tracerKuliah()
    {
        return $this->hasMany(TracerKuliah::class, 'alumni_kuliah_id');
    }
}
