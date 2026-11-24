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
        'nis',
        'nama_alumni',
        'tahun_lulus',
        'program_keahlian',
        'kelas',
        'no_wa',
        'status_alumni',
        'detail_status',
        'testimoni',
    ];

    /**
     * Find a registered student/alumni by NIS using StudentNis as the primary source of verification.
     */
    public static function findRegisteredAlumniByNis(string $nis, bool $lockForUpdate = false): ?array
    {
        $normalizedNis = trim($nis);
        if ($normalizedNis === '') {
            return null;
        }

        $studentQuery = StudentNis::with('programKeahlian')->where('nis', $normalizedNis);
        if ($lockForUpdate) {
            $studentQuery->lockForUpdate();
        }
        $student = $studentQuery->first();
        if ($student) {
            return [
                'nis'                 => $student->nis,
                'nama_alumni'         => $student->nama_siswa,
                'tahun_lulus'         => $student->tahun_lulus,
                'program_keahlian'    => $student->programKeahlian?->nama,
                'program_keahlian_id' => $student->program_keahlian_id,
                'kelas'               => $student->kelas,
                'no_wa'               => null,
                'source'              => 'student_nis',
                'model'               => $student,
            ];
        }

        return null;
    }

    /**
     * Check whether a given NIS has already submitted the Tracer Study form.
     */
    public static function hasSubmittedByNis(string $nis, bool $lockForUpdate = false): bool
    {
        $normalizedNis = trim($nis);
        if ($normalizedNis === '') {
            return false;
        }

        $query = self::where('nis', $normalizedNis);
        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->exists();
    }
}
