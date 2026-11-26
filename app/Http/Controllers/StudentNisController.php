<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use App\Models\StudentNis;
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
            if ($student->program_keahlian_id) {
                if ((int) $student->program_keahlian_id !== (int) $assignedDeptId) {
                    abort(403, 'Anda tidak memiliki akses untuk melihat atau mengelola data siswa dari jurusan lain.');
                }
            } elseif ($student->created_by && (int) $student->created_by !== (int) $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat atau mengelola data siswa ini.');
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
        $programKeahlianId = $request->input('program_keahlian_id', '');

        $baseQuery = StudentNis::query();

        // Pembatasan jurusan khusus Admin Jurusan di sisi backend
        if ($user->isAdminJurusan()) {
            $assignedDeptId = $user->getAssignedProgramKeahlianId();
            $baseQuery->where(function ($q) use ($assignedDeptId, $user) {
                $q->where('program_keahlian_id', $assignedDeptId)
                  ->orWhere(function ($sub) use ($user) {
                      $sub->whereNull('program_keahlian_id')
                          ->where('created_by', $user->id);
                  });
            });
            $programKeahlianId = $assignedDeptId;
        } elseif ($programKeahlianId) {
            $baseQuery->where('program_keahlian_id', $programKeahlianId);
        }

        // Statistik ringkasan (mengikuti cakupan jurusan pengguna)
        $statsQuery = StudentNis::query();
        if ($user->isAdminJurusan()) {
            $assignedDeptId = $user->getAssignedProgramKeahlianId();
            $statsQuery->where(function ($q) use ($assignedDeptId, $user) {
                $q->where('program_keahlian_id', $assignedDeptId)
                  ->orWhere(function ($sub) use ($user) {
                      $sub->whereNull('program_keahlian_id')
                          ->where('created_by', $user->id);
                  });
            });
        } elseif ($programKeahlianId) {
            $statsQuery->where('program_keahlian_id', $programKeahlianId);
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

        $programKeahlians = $user->isAdminJurusan()
            ? ProgramKeahlian::where('id', $user->getAssignedProgramKeahlianId())->get()
            : ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.nis-tracer.index', compact(
            'students',
            'search',
            'statusFilter',
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
            'nama_siswa' => 'required|string|max:255',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS ini sudah terdaftar di dalam sistem.',
            'nis.max' => 'NIS maksimal 50 karakter.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
        ]);

        StudentNis::create([
            'nis'                 => trim($validated['nis']),
            'nama_siswa'          => trim($validated['nama_siswa']),
            'program_keahlian_id' => null,
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

            // 1. Deteksi kolom NIS dan Nama secara cerdas
            $nisCol = null;
            $namaCol = null;
            $headerRowIndex = null;

            $maxHeaderSearch = min(15, count($rows));
            for ($r = 0; $r < $maxHeaderSearch; $r++) {
                $row = $rows[$r];
                if (!is_array($row)) continue;

                $foundNis = null;
                $foundNama = null;

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
                }

                // Kolom NIS dan Nama HARUS ditemukan dan merupakan dua kolom BERBEDA
                if ($foundNis !== null && $foundNama !== null && $foundNis !== $foundNama) {
                    $nisCol = $foundNis;
                    $namaCol = $foundNama;
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
                    }

                    if ($tempNis !== null && $tempNama !== null && $tempNis !== $tempNama) {
                        $nisCol = $tempNis;
                        $namaCol = $tempNama;
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

            $insertedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            foreach ($dataRows as $row) {
                if (!is_array($row)) continue;

                $rawNis = $row[$nisCol] ?? '';
                $rawNama = $row[$namaCol] ?? '';

                // Format nilai NIS (hilangkan desimal seperti 12345.0 atau notasi ilmiah)
                if (is_numeric($rawNis)) {
                    $nis = (string) (is_float($rawNis) || is_int($rawNis) ? number_format((float) $rawNis, 0, '', '') : $rawNis);
                } else {
                    $nis = trim((string) $rawNis);
                }

                $nama = trim((string) $rawNama);

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

                $student = StudentNis::where('nis', $nis)->first();

                if ($student) {
                    $student->nama_siswa = $nama;
                    if ($assignedDeptId && empty($student->program_keahlian_id)) {
                        $student->program_keahlian_id = $assignedDeptId;
                    }
                    $student->save();
                    $updatedCount++;
                } else {
                    StudentNis::create([
                        'nis'                 => $nis,
                        'nama_siswa'          => $nama,
                        'program_keahlian_id' => $assignedDeptId,
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
            'nama_siswa' => 'required|string|max:255',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS ini sudah digunakan oleh siswa lain.',
            'nis.max' => 'NIS maksimal 50 karakter.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
        ]);

        $student->update([
            'nis'        => trim($validated['nis']),
            'nama_siswa' => trim($validated['nama_siswa']),
        ]);

        return redirect()->route('nis-tracer.index')
            ->with('success', 'Data NIS siswa berhasil diperbarui.');
    }

    /**
     * Hapus data pendaftaran NIS siswa tanpa menghapus riwayat Tracer Study yang sudah masuk.
     */
    public function destroy($id)
    {
        $student = StudentNis::findOrFail($id);
        $this->authorizeRecordAccess($student);

        $student->delete();

        return redirect()->route('nis-tracer.index')
            ->with('success', 'Data NIS siswa berhasil dihapus.');
    }
}
