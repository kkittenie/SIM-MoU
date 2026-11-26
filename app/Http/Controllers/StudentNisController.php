<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use App\Models\StudentNis;
use App\Models\TracerStudy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentNisController extends Controller
{
    /**
     * Pastikan pengguna memiliki hak akses (Admin atau Admin Jurusan).
     */
    private function ensureAuthorizedRole(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isAdminJurusan())) {
            abort(403, 'Anda tidak memiliki akses ke halaman Data NIS Tracer Study.');
        }
    }

    /**
     * Pastikan Admin Jurusan hanya dapat mengakses data siswa dari jurusannya sendiri.
     */
    private function authorizeRecordAccess(StudentNis $student): void
    {
        $this->ensureAuthorizedRole();

        $user = auth()->user();
        if ($user->isAdminJurusan()) {
            $assignedDeptId = $user->getAssignedProgramKeahlianId();
            if ((int) $student->program_keahlian_id !== (int) $assignedDeptId) {
                abort(403, 'Anda tidak memiliki akses untuk melihat atau mengelola data siswa dari jurusan lain.');
            }
        }
    }

    /**
     * Tampilkan daftar NIS siswa dengan filter, pencarian, dan statistik ringkas.
     */
    public function index(Request $request)
    {
        $this->ensureAuthorizedRole();

        $user = auth()->user();
        $search = trim((string) $request->input('search', ''));
        $statusFilter = $request->input('status_tracer', '');
        $programKeahlianId = (string) $request->input('program_keahlian_id', '');
        $tahunLulusFilter = $request->input('tahun_lulus', '');

        $baseQuery = StudentNis::query();
        $statsQuery = StudentNis::query();

        // Pembatasan ketat: Admin Jurusan HANYA melihat siswa dari jurusannya sendiri
        if ($user->isAdminJurusan()) {
            $assignedDeptId = $user->getAssignedProgramKeahlianId();
            $baseQuery->where('program_keahlian_id', $assignedDeptId);
            $statsQuery->where('program_keahlian_id', $assignedDeptId);
            $programKeahlianId = (string) $assignedDeptId;
        } elseif ($programKeahlianId !== '') {
            $baseQuery->where('program_keahlian_id', $programKeahlianId);
            $statsQuery->where('program_keahlian_id', $programKeahlianId);
        }

        // Filter Tahun Lulus
        if ($tahunLulusFilter !== '') {
            $baseQuery->where('tahun_lulus', $tahunLulusFilter);
            $statsQuery->where('tahun_lulus', $tahunLulusFilter);
        }

        $totalStudents = (clone $statsQuery)->count();
        $totalSubmitted = (clone $statsQuery)->whereHas('tracerStudy')->count();
        $totalNotSubmitted = (clone $statsQuery)->whereDoesntHave('tracerStudy')->count();

        // Filter pencarian
        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_siswa', 'like', "%{$search}%");
            });
        }

        // Filter status pengisian Tracer Study
        if ($statusFilter === 'Sudah Mengisi') {
            $baseQuery->whereHas('tracerStudy');
        } elseif ($statusFilter === 'Belum Mengisi') {
            $baseQuery->whereDoesntHave('tracerStudy');
        }

        $students = $baseQuery->with(['programKeahlian', 'tracerStudy'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Admin Jurusan hanya mendapatkan jurusannya sendiri, Admin Utama mendapatkan semua jurusan
        $programKeahlians = $user->isAdminJurusan()
            ? ProgramKeahlian::where('id', $user->getAssignedProgramKeahlianId())->get()
            : ProgramKeahlian::orderBy('nama', 'asc')->get();

        // Ambil daftar tahun kelulusan unik untuk filter dropdown
        $availableYearsQuery = StudentNis::query();
        if ($user->isAdminJurusan()) {
            $assignedDeptId = $user->getAssignedProgramKeahlianId();
            $availableYearsQuery->where('program_keahlian_id', $assignedDeptId);
        } elseif ($programKeahlianId) {
            $availableYearsQuery->where('program_keahlian_id', $programKeahlianId);
        }

        $availableYears = $availableYearsQuery->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus');

        return view('pages.nis-tracer.index', compact(
            'students',
            'search',
            'statusFilter',
            'tahunLulusFilter',
            'availableYears',
            'programKeahlianId',
            'programKeahlians',
            'totalStudents',
            'totalSubmitted',
            'totalNotSubmitted'
        ));
    }

    /**
     * Tampilkan form tambah NIS siswa baru.
     */
    public function create()
    {
        $this->ensureAuthorizedRole();

        return view('pages.nis-tracer.create');
    }

    /**
     * Simpan data NIS siswa baru ke database.
     */
    public function store(Request $request)
    {
        $this->ensureAuthorizedRole();

        $user = auth()->user();

        $validated = $request->validate([
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('student_nis', 'nis'),
            ],
            'nama_siswa'  => 'required|string|max:255',
            'tahun_lulus' => 'nullable|integer|digits:4|min:1990|max:' . (date('Y') + 5),
        ], [
            'nis.required'        => 'NIS wajib diisi.',
            'nis.unique'          => 'NIS ini sudah terdaftar di dalam sistem.',
            'nis.max'             => 'NIS maksimal 50 karakter.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
            'tahun_lulus.digits'  => 'Tahun lulus harus berupa 4 digit angka (contoh: 2024).',
            'tahun_lulus.integer' => 'Tahun lulus harus berupa angka tahun.',
        ]);

        $assignedDeptId = $user->isAdminJurusan() ? $user->getAssignedProgramKeahlianId() : null;

        StudentNis::create([
            'nis'                 => trim($validated['nis']),
            'nama_siswa'          => trim($validated['nama_siswa']),
            'tahun_lulus'         => !empty($validated['tahun_lulus']) ? (int) $validated['tahun_lulus'] : null,
            'program_keahlian_id' => $assignedDeptId,
            'created_by'          => $user->id,
        ]);

        return redirect()->route('nis-tracer.index')
            ->with('success', 'Data NIS siswa berhasil didaftarkan.');
    }

    /**
     * IMPORT - Import data NIS siswa dari file Excel.
     * Mengambil kolom NIS dan Nama Siswa saja meskipun file Excel berisi banyak kolom lainnya.
     */
    public function import(Request $request)
    {
        $this->ensureAuthorizedRole();

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'Format file harus berupa Excel (.xlsx atau .xls).',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->path());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (empty($rows)) {
                return redirect()->back()->with('error', 'File Excel kosong atau tidak dapat dibaca.');
            }

            // 1. Deteksi kolom NIS, Nama, Tahun Lulus, dan Jurusan secara cerdas
            $nisCol = null;
            $namaCol = null;
            $tahunCol = null;
            $jurusanCol = null;
            $headerRowIndex = null;

            $maxHeaderSearch = min(15, count($rows));
            for ($r = 0; $r < $maxHeaderSearch; $r++) {
                $row = $rows[$r];
                if (!is_array($row)) continue;

                $foundNis = null;
                $foundNama = null;
                $foundTahun = null;
                $foundJurusan = null;

                foreach ($row as $cIdx => $cell) {
                    $cellVal = strtolower(trim((string) $cell));
                    if ($cellVal === '') continue;

                    // Lewati baris teks pengantar, instruksi panjang, atau catatan
                    if (strlen($cellVal) > 40 || preg_match('/^(catatan|note|petunjuk|template|perhatian)/i', $cellVal)) {
                        continue;
                    }

                    // Deteksi kolom NIS (harus label kolom, bukan kalimat)
                    if ($foundNis === null) {
                        if (preg_match('/^(nis|nomor\s*induk(\s*siswa)?|no\.?\s*induk(\s*siswa)?|no_induk|id_siswa|student_id)$/i', $cellVal)) {
                            $foundNis = $cIdx;
                        } elseif (preg_match('/\bnis\b/i', $cellVal) && !preg_match('/\bnisn\b/i', $cellVal)) {
                            $foundNis = $cIdx;
                        } elseif ($cellVal === 'nisn') {
                            $foundNis = $cIdx;
                        }
                    }

                    // Deteksi kolom Nama (harus label nama siswa/peserta didik)
                    if ($foundNama === null) {
                        if (preg_match('/^(nama|nama\s*siswa|nama\s*lengkap|nama\s*peserta\s*didik|name|student_name|nama_siswa)$/i', $cellVal)) {
                            $foundNama = $cIdx;
                        } elseif (preg_match('/\bnama\b/i', $cellVal) && !preg_match('/(sekolah|ayah|ibu|wali|perusahaan|kampus|mitra|jurusan)/i', $cellVal)) {
                            $foundNama = $cIdx;
                        }
                    }

                    // Deteksi kolom Tahun Lulus / Angkatan (opsional)
                    if ($foundTahun === null) {
                        if (preg_match('/^(tahun\s*lulus|thn\s*lulus|tahun_lulus|angkatan|thn_lulus|th\s*lulus)$/i', $cellVal)) {
                            $foundTahun = $cIdx;
                        } elseif (preg_match('/\b(tahun|angkatan)\b/i', $cellVal) && !preg_match('/(lahir|ajaran|masuk)/i', $cellVal)) {
                            $foundTahun = $cIdx;
                        }
                    }

                    // Deteksi kolom Jurusan / Program Keahlian (opsional)
                    if ($foundJurusan === null) {
                        if (preg_match('/^(jurusan|program\s*keahlian|kompetensi\s*keahlian|prodi|konsentrasi\s*keahlian)$/i', $cellVal)) {
                            $foundJurusan = $cIdx;
                        } elseif (preg_match('/\b(jurusan|keahlian)\b/i', $cellVal) && !preg_match('/(sekolah|mitra|perusahaan|kampus)/i', $cellVal)) {
                            $foundJurusan = $cIdx;
                        }
                    }
                }

                // Kolom NIS dan Nama HARUS ditemukan dan merupakan dua kolom BERBEDA
                if ($foundNis !== null && $foundNama !== null && $foundNis !== $foundNama) {
                    $nisCol = $foundNis;
                    $namaCol = $foundNama;
                    if ($foundTahun !== null && $foundTahun !== $foundNis && $foundTahun !== $foundNama) {
                        $tahunCol = $foundTahun;
                    }
                    if ($foundJurusan !== null && $foundJurusan !== $foundNis && $foundJurusan !== $foundNama) {
                        $jurusanCol = $foundJurusan;
                    }
                    $headerRowIndex = $r;
                    break;
                }
            }

            // Fallback jika tidak terdeteksi header lengkap di satu baris:
            if ($nisCol === null || $namaCol === null || $nisCol === $namaCol) {
                for ($r = 0; $r < $maxHeaderSearch; $r++) {
                    $row = $rows[$r];
                    if (!is_array($row)) continue;

                    $tempNis = null;
                    $tempNama = null;
                    $tempTahun = null;
                    $tempJurusan = null;

                    foreach ($row as $cIdx => $cell) {
                        $cellVal = strtolower(trim((string) $cell));
                        if (strlen($cellVal) > 40 || preg_match('/^(catatan|note|petunjuk|template)/i', $cellVal)) {
                            continue;
                        }
                        if ($tempNis === null && preg_match('/\bnis\b/i', $cellVal) && !preg_match('/\bnisn\b/i', $cellVal)) {
                            $tempNis = $cIdx;
                        }
                        if ($tempNama === null && preg_match('/\bnama\b/i', $cellVal) && !preg_match('/(sekolah|ayah|ibu|wali)/i', $cellVal)) {
                            $tempNama = $cIdx;
                        }
                        if ($tempTahun === null && preg_match('/(tahun\s*lulus|angkatan)/i', $cellVal)) {
                            $tempTahun = $cIdx;
                        }
                        if ($tempJurusan === null && preg_match('/(jurusan|program\s*keahlian|keahlian)/i', $cellVal) && !preg_match('/(sekolah|mitra|perusahaan)/i', $cellVal)) {
                            $tempJurusan = $cIdx;
                        }
                    }

                    if ($tempNis !== null && $tempNama !== null && $tempNis !== $tempNama) {
                        $nisCol = $tempNis;
                        $namaCol = $tempNama;
                        if ($tempTahun !== null && $tempTahun !== $tempNis && $tempTahun !== $tempNama) {
                            $tahunCol = $tempTahun;
                        }
                        if ($tempJurusan !== null && $tempJurusan !== $tempNis && $tempJurusan !== $tempNama) {
                            $jurusanCol = $tempJurusan;
                        }
                        $headerRowIndex = $r;
                        break;
                    }
                }
            }

            // Jika masih belum ketemu, coba urutan default (Kolom No, NIS, Nama Siswa atau NIS, Nama Siswa)
            if ($nisCol === null || $namaCol === null || $nisCol === $namaCol) {
                $sampleRow = $rows[0] ?? [];
                if (count($sampleRow) >= 3) {
                    $nisCol = 1;
                    $namaCol = 2;
                    $headerRowIndex = 0;
                } elseif (count($sampleRow) >= 2) {
                    $nisCol = 0;
                    $namaCol = 1;
                    $headerRowIndex = 0;
                } else {
                    return redirect()->back()->with('error', 'Format kolom Excel tidak dikenali. Pastikan terdapat kolom NIS dan Nama Siswa.');
                }
            }

            $user = auth()->user();
            $assignedDeptId = $user->isAdminJurusan() ? $user->getAssignedProgramKeahlianId() : null;

            $startRow = ($headerRowIndex !== null) ? $headerRowIndex + 1 : 0;
            $dataRows = array_slice($rows, $startRow);

            $allProgramKeahlians = ProgramKeahlian::all();
            $insertedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            foreach ($dataRows as $row) {
                if (!is_array($row)) continue;

                $rawNis = $row[$nisCol] ?? '';
                $rawNama = $row[$namaCol] ?? '';
                $rawTahun = ($tahunCol !== null && isset($row[$tahunCol])) ? $row[$tahunCol] : null;
                $rawJurusan = ($jurusanCol !== null && isset($row[$jurusanCol])) ? trim((string) $row[$jurusanCol]) : null;

                // Format nilai NIS (hilangkan desimal seperti 12345.0 atau notasi ilmiah)
                if (is_numeric($rawNis)) {
                    $nis = (string) (is_float($rawNis) || is_int($rawNis) ? number_format((float) $rawNis, 0, '', '') : $rawNis);
                } else {
                    $nis = trim((string) $rawNis);
                }

                $nama = trim((string) $rawNama);

                // Ekstrak tahun kelulusan 4 digit jika ada
                $tahunLulus = null;
                if ($rawTahun !== null && $rawTahun !== '') {
                    if (preg_match('/\b(19\d{2}|20\d{2})\b/', (string) $rawTahun, $matches)) {
                        $tahunLulus = (int) $matches[1];
                    }
                }

                // Ekstrak jurusan jika kolom jurusan terdeteksi di Excel
                $programKeahlianId = null;
                if (!empty($rawJurusan)) {
                    $search = strtolower($rawJurusan);
                    $matchedPk = $allProgramKeahlians->first(function ($pk) use ($search) {
                        $pkNama = strtolower($pk->nama);
                        $pkSingkatan = strtolower($pk->singkatan ?? '');
                        return ($pkSingkatan !== '' && $search === $pkSingkatan)
                            || $search === $pkNama
                            || ($pkSingkatan !== '' && preg_match('/\b' . preg_quote($pkSingkatan, '/') . '\b/i', $search))
                            || str_contains($search, $pkNama);
                    });

                    // Jika jurusan di Excel belum terdaftar di setting, otomatis daftarkan ke master ProgramKeahlian!
                    if (!$matchedPk) {
                        $cleanName = strtoupper(trim($rawJurusan));
                        $matchedPk = ProgramKeahlian::create([
                            'nama'      => $cleanName,
                            'singkatan' => strlen($cleanName) <= 10 ? $cleanName : null,
                        ]);
                        $allProgramKeahlians->push($matchedPk);
                    }

                    $programKeahlianId = $matchedPk->id;
                }

                $lowerNis = strtolower($nis);
                $lowerNama = strtolower($nama);

                // Lewati jika kosong, header berulang, atau nilai nomor urut dummy
                if (
                    $nis === '' || $nama === '' ||
                    $lowerNis === 'nis' || $lowerNama === 'nama' || $lowerNama === 'nama siswa' ||
                    $lowerNis === 'no' || $lowerNis === 'nomor' || $lowerNama === 'no' ||
                    $lowerNis === $lowerNama
                ) {
                    $skippedCount++;
                    continue;
                }

                // Batasi panjang string sesuai schema database
                $nis = substr($nis, 0, 50);
                $nama = substr($nama, 0, 255);

                // Jika Admin Jurusan yang mengimpor, selalu tetapkan ke jurusannya sendiri
                $finalPkId = $assignedDeptId ?: $programKeahlianId;

                $student = StudentNis::where('nis', $nis)->first();

                if ($student) {
                    $student->nama_siswa = $nama;
                    if ($tahunLulus) {
                        $student->tahun_lulus = $tahunLulus;
                    }
                    if ($finalPkId) {
                        $student->program_keahlian_id = $finalPkId;
                    }
                    $student->save();
                    $updatedCount++;
                } else {
                    StudentNis::create([
                        'nis'                 => $nis,
                        'nama_siswa'          => $nama,
                        'tahun_lulus'         => $tahunLulus,
                        'program_keahlian_id' => $finalPkId,
                        'created_by'          => $user->id,
                    ]);
                    $insertedCount++;
                }
            }

            $totalProcessed = $insertedCount + $updatedCount;
            if ($totalProcessed === 0) {
                return redirect()->route('nis-tracer.index')
                    ->with('error', 'Tidak ada baris data valid yang berhasil diimpor. Pastikan file Excel berisi data NIS dan Nama Siswa.');
            }

            $msg = "Import data NIS berhasil: {$insertedCount} data baru ditambahkan";
            if ($updatedCount > 0) {
                $msg .= ", {$updatedCount} data diperbarui";
            }
            $msg .= '.';

            return redirect()->route('nis-tracer.index')->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman detail NIS siswa beserta status/jawaban Tracer Study.
     */
    public function show($id)
    {
        $student = StudentNis::with(['programKeahlian', 'tracerStudy'])->findOrFail($id);
        $this->authorizeRecordAccess($student);

        $detailSubmission = null;
        if ($student->tracerStudy) {
            $status = $student->tracerStudy->status_alumni;
            if ($status === 'Bekerja') {
                $detailSubmission = AlumniBekerja::where('nis', $student->nis)->first();
            } elseif ($status === 'Kuliah') {
                $detailSubmission = AlumniKuliah::with('universitas')->where('nis', $student->nis)->first();
            } elseif ($status === 'Wirausaha') {
                $detailSubmission = AlumniWirausaha::where('nis', $student->nis)->first();
            }
        }

        return view('pages.nis-tracer.show', compact('student', 'detailSubmission'));
    }

    /**
     * Tampilkan form ubah data NIS siswa.
     */
    public function edit($id)
    {
        $student = StudentNis::with(['programKeahlian', 'tracerStudy'])->findOrFail($id);
        $this->authorizeRecordAccess($student);

        return view('pages.nis-tracer.edit', compact('student'));
    }

    /**
     * Perbarui data NIS siswa di database.
     */
    public function update(Request $request, $id)
    {
        $student = StudentNis::findOrFail($id);
        $this->authorizeRecordAccess($student);

        $validated = $request->validate([
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('student_nis', 'nis')->ignore($student->id),
            ],
            'nama_siswa'  => 'required|string|max:255',
            'tahun_lulus' => 'nullable|integer|digits:4|min:1990|max:' . (date('Y') + 5),
        ], [
            'nis.required'        => 'NIS wajib diisi.',
            'nis.unique'          => 'NIS ini sudah digunakan oleh siswa lain.',
            'nis.max'             => 'NIS maksimal 50 karakter.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
            'tahun_lulus.digits'  => 'Tahun lulus harus berupa 4 digit angka (contoh: 2024).',
            'tahun_lulus.integer' => 'Tahun lulus harus berupa angka tahun.',
        ]);

        $oldNis = $student->nis;
        $newNis = trim($validated['nis']);

        if ($oldNis !== $newNis) {
            TracerStudy::where('nis', $oldNis)->update(['nis' => $newNis]);
            AlumniBekerja::where('nis', $oldNis)->update(['nis' => $newNis]);
            AlumniKuliah::where('nis', $oldNis)->update(['nis' => $newNis]);
            AlumniWirausaha::where('nis', $oldNis)->update(['nis' => $newNis]);
        }

        $student->update([
            'nis'         => $newNis,
            'nama_siswa'  => trim($validated['nama_siswa']),
            'tahun_lulus' => !empty($validated['tahun_lulus']) ? (int) $validated['tahun_lulus'] : null,
        ]);

        return redirect()->route('nis-tracer.index')
            ->with('success', 'Data NIS siswa berhasil diperbarui.');
    }

    /**
     * Hapus data pendaftaran NIS siswa beserta respon formulir Tracer Study terkait.
     */
    public function destroy($id)
    {
        $student = StudentNis::findOrFail($id);
        $this->authorizeRecordAccess($student);

        // Hapus formulir Tracer Study dan rincian alumni terkait jika ada
        if (!empty($student->nis)) {
            TracerStudy::where('nis', $student->nis)->delete();
            AlumniBekerja::where('nis', $student->nis)->delete();
            AlumniKuliah::where('nis', $student->nis)->delete();
            AlumniWirausaha::where('nis', $student->nis)->delete();
        }

        $student->delete();

        return redirect()->route('nis-tracer.index')
            ->with('success', 'Data NIS siswa beserta data pengisian formulir Tracer Study berhasil dihapus.');
    }
}
