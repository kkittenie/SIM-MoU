<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNis extends Model
{
    use HasFactory;

    protected $table = 'student_nis';

    protected $fillable = [
        'nis',
        'nama_siswa',
        'program_keahlian_id',
        'tahun_lulus',
        'kelas',
        'created_by',
    ];

    /**
     * Relasi ke Program Keahlian (Jurusan).
     */
    public function programKeahlian()
    {
        return $this->belongsTo(ProgramKeahlian::class, 'program_keahlian_id');
    }

    /**
     * Relasi ke pengguna pembuat data.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke data pengisian Tracer Study berdasarkan NIS.
     * Tidak menggunakan cascade delete agar penghapusan NIS tidak menghapus riwayat Tracer Study.
     */
    public function tracerStudy()
    {
        return $this->hasOne(TracerStudy::class, 'nis', 'nis');
    }

    /**
     * Cek apakah siswa dengan NIS ini sudah mengisi Tracer Study.
     */
    public function getHasSubmittedAttribute(): bool
    {
        if ($this->relationLoaded('tracerStudy')) {
            return $this->tracerStudy !== null;
        }

        return TracerStudy::where('nis', $this->nis)->exists();
    }

    /**
     * Label status pengisian Tracer Study ("Sudah Mengisi" / "Belum Mengisi").
     */
    public function getStatusTracerLabelAttribute(): string
    {
        return $this->has_submitted ? 'Sudah Mengisi' : 'Belum Mengisi';
    }
}
