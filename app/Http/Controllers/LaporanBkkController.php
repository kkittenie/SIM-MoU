<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\PerusahaanMitra;
use App\Models\TracerStudy;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;

class LaporanBkkController extends Controller
{
    /**
     * Tampilkan halaman laporan BKK.
     */
    public function index(Request $request)
    {
        $tahunLulus = $request->input('tahun_lulus');

        // Metrik Ringkasan
        $queryAlumni = AlumniBekerja::query();
        $queryTracer = TracerStudy::query()->whereIn('status_alumni', ['Bekerja', 'Mencari Kerja']);

        if ($tahunLulus) {
            $queryAlumni->where('tahun_lulus', $tahunLulus);
            $queryTracer->where('tahun_lulus', $tahunLulus);
        }

        $totalAlumniBekerja = $queryAlumni->count();
        $totalMitra = PerusahaanMitra::count();
        $totalTracer = $queryTracer->count();
        $totalLoker = LowonganKerja::where('status', 'Aktif')->count();

        // Hitung persentase tracer study
        $tracerStats = [
            'Bekerja' => (clone $queryTracer)->where('status_alumni', 'Bekerja')->count(),
            'Mencari Kerja' => (clone $queryTracer)->where('status_alumni', 'Mencari Kerja')->count(),
        ];

        // Daftar Industri Terbanyak
        $industriStats = (clone $queryAlumni)->select('bidang_industri', \DB::raw('count(*) as total'))
            ->groupBy('bidang_industri')
            ->orderBy('total', 'desc')
            ->get();

        // Daftar Perusahaan Paling Banyak Merekrut
        $perusahaanStats = (clone $queryAlumni)->select('perusahaan_nama', \DB::raw('count(*) as total'))
            ->groupBy('perusahaan_nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Ambil list tahun lulus untuk dropdown filter dari kedua tabel
        $tahunLulusList = array_unique(array_merge(
            AlumniBekerja::distinct()->pluck('tahun_lulus')->toArray(),
            TracerStudy::distinct()->pluck('tahun_lulus')->toArray()
        ));
        sort($tahunLulusList);

        // Data list untuk dicetak
        $alumniList = (clone $queryAlumni)->latest()->get();

        return view('pages.bkk.laporan.index', compact(
            'totalAlumniBekerja',
            'totalMitra',
            'totalTracer',
            'totalLoker',
            'tracerStats',
            'industriStats',
            'perusahaanStats',
            'tahunLulusList',
            'tahunLulus',
            'alumniList'
        ));
    }
}
