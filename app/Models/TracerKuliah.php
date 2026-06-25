<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerKuliah extends Model
{
    protected $table = 'tracer_studies_kuliah';

    protected $fillable = [
        'alumni_kuliah_id',
        'status_kuliah',
        'kampus_tujuan',
        'program_studi',
        'detail_status',
        'testimoni',
        'tanggal_update',
    ];
        protected $casts = [
        'tanggal_update' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function alumniKuliah()
    {
        return $this->belongsTo(AlumniKuliah::class, 'alumni_kuliah_id');
    }
}
