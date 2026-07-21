<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use App\Models\LowonganKerja;
use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page publik.
     */
    public function index()
    {
        $totalMitra = KerjaSama::count();
        $totalLowongan = LowonganKerja::where('status', 'Aktif')->count();
        
        $totalAlumniBekerja = AlumniBekerja::count();
        $totalAlumniKuliah = AlumniKuliah::count();
        $totalAlumniWirausaha = AlumniWirausaha::count();
        $totalAlumni = $totalAlumniBekerja + $totalAlumniKuliah + $totalAlumniWirausaha;

        // Fetch latest 3 active jobs to display on the landing section
        $lowonganTerbaru = LowonganKerja::with('perusahaanMitra')
            ->where('status', 'Aktif')
            ->latest()
            ->take(3)
            ->get();

        return view('pages.landing.index', compact(
            'totalMitra',
            'totalLowongan',
            'totalAlumni',
            'totalAlumniBekerja',
            'totalAlumniKuliah',
            'totalAlumniWirausaha',
            'lowonganTerbaru'
        ));
    }

    /**
     * Tampilkan semua lowongan kerja publik dengan pencarian & filter.
     */
    public function lowonganIndex(Request $request)
    {
        $search = $request->input('search');

        $lowongans = LowonganKerja::with('perusahaanMitra')
            ->where('status', 'Aktif')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('perusahaan_nama', 'like', "%{$search}%")
                      ->orWhere('posisi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('pages.landing.lowongan-index', compact('lowongans', 'search'));
    }

    /**
     * Tampilkan detail lowongan kerja di halaman publik.
     */
    public function lowonganDetail($id)
    {
        $lowongan = LowonganKerja::with('perusahaanMitra')->findOrFail($id);
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.landing.lowongan-detail', compact('lowongan', 'programKeahlians'));
    }

    /**
     * Tampilkan form tracer study di halaman publik.
     */
    public function tracerStudyForm()
    {
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.landing.tracer-study', compact('programKeahlians'));
    }

    /**
     * Submit lamaran kerja dari form publik.
     */
    public function submitLamaran(Request $request, $id)
    {
        $lowongan = LowonganKerja::findOrFail($id);

        if ($lowongan->status !== 'Aktif') {
            return back()->with('error', 'Maaf, lowongan kerja ini telah ditutup.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'tahun_lulus' => 'required|numeric|digits:4',
            'program_keahlian' => 'required|string|max:255',
            'surat_lamaran' => 'required|string',
            'file_cv' => 'nullable|file|mimes:pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'program_keahlian.required' => 'Jurusan wajib dipilih.',
            'surat_lamaran.required' => 'Surat lamaran wajib diisi.',
            'file_cv.mimes' => 'File CV harus berformat PDF.',
            'file_cv.max' => 'Ukuran file CV maksimal 2MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_cv')) {
            $filePath = $request->file('file_cv')->store('cv_pelamar', 'public');
        }

        \App\Models\Pelamar::create([
            'lowongan_kerja_id' => $lowongan->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'program_keahlian' => $validated['program_keahlian'],
            'surat_lamaran' => $validated['surat_lamaran'],
            'file_cv' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim! BKK akan meninjau lamaran Anda.');
    }
}
