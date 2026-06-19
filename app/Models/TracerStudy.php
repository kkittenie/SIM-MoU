<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    protected $table = 'tracer_studies';

    protected $fillable = [
        'nama_alumni',
        'tahun_lulus',
        'status_alumni',
        'detail_status',
        'testimoni',
    ];
}
