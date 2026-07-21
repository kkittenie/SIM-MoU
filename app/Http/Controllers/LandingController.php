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
}
