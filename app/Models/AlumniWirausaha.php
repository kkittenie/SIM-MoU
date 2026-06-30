<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FilterByTahunAjaran;

class AlumniWirausaha extends Model
{
    use HasFactory, FilterByTahunAjaran;

    protected $table = 'alumni_wirausahas';

    protected $fillable = [
        'nama_alumni',
        'nama_usaha',
        'bidang_usaha',
        'lama_usaha',
        'tahun_lulus',
    ];
}
