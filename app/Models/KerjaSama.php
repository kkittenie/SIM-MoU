<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KerjaSama extends Model
{
    use HasFactory;

    protected $table = 'kerja_sama';

    protected $fillable = [
        'kategori_mitra_id',
        'nama_mitra',
        'jenis_mitra',
        'alamat',
        'email',
        'nomor_telepon',
        'pic',
        'nomor_mou',
        'tanggal_mulai',
        'tanggal_berakhir',
        'deskripsi',
        'dokumen_pdf',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($kerjaSama) {
            if (!$kerjaSama->kategori_mitra_id && $kerjaSama->jenis_mitra) {
                $category = KategoriMitra::where('nama', $kerjaSama->jenis_mitra)->first();
                if ($category) {
                    $kerjaSama->kategori_mitra_id = $category->id;
                }
            }
        });

        static::updating(function ($kerjaSama) {
            if (!$kerjaSama->kategori_mitra_id && $kerjaSama->jenis_mitra) {
                $category = KategoriMitra::where('nama', $kerjaSama->jenis_mitra)->first();
                if ($category) {
                    $kerjaSama->kategori_mitra_id = $category->id;
                }
            }
        });
    }

    public function kategoriMitra()
    {
        return $this->belongsTo(KategoriMitra::class, 'kategori_mitra_id');
    }

    public function getJenisMitraAttribute(): string
    {
        return $this->kategoriMitra ? $this->kategoriMitra->nama : ($this->attributes['jenis_mitra'] ?? 'Lainnya');
    }

    /**
     * Get the computed status of the MoU.
     * Status can be: 'Expired', 'Akan Berakhir', or 'Aktif'
     */
    public function getStatusAttribute(): string
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($this->tanggal_berakhir);

        if ($endDate->lt($today)) {
            return 'Expired';
        }

        if ($endDate->diffInDays($today, true) <= 30) {
            return 'Akan Berakhir';
        }

        return 'Aktif';
    }
}
