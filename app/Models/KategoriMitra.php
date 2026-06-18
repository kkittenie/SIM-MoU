<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriMitra extends Model
{
    use HasFactory;

    protected $table = 'kategori_mitra';

    protected $fillable = [
        'nama',
    ];

    public function kerjaSama()
    {
        return $this->hasMany(KerjaSama::class, 'kategori_mitra_id');
    }
}
