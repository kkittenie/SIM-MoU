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
        'nis',
        'nama_alumni',
        'kelas',
        'no_wa',
        'nama_usaha',
        'bidang_usaha',
        'lama_usaha',
        'tahun_lulus',
        'program_keahlian',
    ];
}
