<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Setting;

class TracerKuliah extends Model
{
    use HasFactory;

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

    public function scopeTahunAjaranAktif(Builder $query)
    {
        if (request() && request()->has('tahun_lulus')) {
            return $query;
        }

        $activeYear = Setting::getActiveTahunAjaran();
        if ($activeYear) {
            return $query->whereHas('alumniKuliah', function ($q) use ($activeYear) {
                $q->where('tahun_lulus', $activeYear);
            });
        }

        return $query;
    }
}
