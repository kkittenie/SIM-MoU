<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByTahunAjaran;

class TracerStudy extends Model
{
    use HasFactory, FilterByTahunAjaran;

    protected $table = 'tracer_studies';

    protected $fillable = [
        'nama_alumni',
        'tahun_lulus',
        'status_alumni',
        'detail_status',
        'testimoni',
    ];
}
