<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKeahlian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'singkatan',
    ];

    public function kerjaSama()
    {
        return $this->hasMany(KerjaSama::class, 'program_keahlian_id');
    }
}
