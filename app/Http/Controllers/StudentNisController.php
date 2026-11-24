<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use App\Models\StudentNis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
