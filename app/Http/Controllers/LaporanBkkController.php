<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\PerusahaanMitra;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;

class LaporanBkkController extends Controller
{
    /**
     * Tampilkan halaman laporan BKK.
     */
    public function index(Request $request)
    {
        $tahunLulus = $request->has('tahun_lulus') ? $request->input('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();

        // Metrik Ringkasan
        $queryAlumni = AlumniBekerja::query()->tahunAjaranAktif();

        if ($tahunLulus) {
            $queryAlumni->where('tahun_lulus', $tahunLulus);
        }

        $totalAlumniBekerja = $queryAlumni->count();
        $totalMitra = PerusahaanMitra::count();
        $totalLoker = LowonganKerja::where('status', 'Aktif')->count();

        // Hitung status pekerjaan alumni
        $statusStats = [
            'Tetap' => (clone $queryAlumni)->where('status_pekerjaan', 'Tetap')->count(),
            'Kontrak' => (clone $queryAlumni)->where('status_pekerjaan', 'Kontrak')->count(),
            'Freelance' => (clone $queryAlumni)->where('status_pekerjaan', 'Freelance')->count(),
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

        // Ambil list tahun lulus untuk dropdown filter
        $tahunLulusList = AlumniBekerja::distinct()->pluck('tahun_lulus')->sort()->toArray();

        // Data list untuk dicetak
        $alumniList = (clone $queryAlumni)->latest()->paginate(10)->withQueryString();

        return view('pages.bkk.laporan.index', compact(
            'totalAlumniBekerja',
            'totalMitra',
            'totalLoker',
            'statusStats',
            'industriStats',
            'perusahaanStats',
            'tahunLulusList',
            'tahunLulus',
            'alumniList'
        ));
    }
}
