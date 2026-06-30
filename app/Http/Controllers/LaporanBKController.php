<?php

namespace App\Http\Controllers;

use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\Universitas;
use Illuminate\Http\Request;

class LaporanBKController extends Controller
{
    /**
     * Main laporan index - Alumni Kuliah & Wirausaha
     */
    public function index(Request $request)
    {
        $tahunLulus = $request->has('tahun_lulus') ? $request->get('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();

        // Query base
        $queryAlumni = AlumniKuliah::query()->tahunAjaranAktif();
        $queryWirausaha = AlumniWirausaha::query()->tahunAjaranAktif();

        // Filter by tahun lulus
        if ($tahunLulus) {
            $queryAlumni->where('tahun_lulus', $tahunLulus);
            $queryWirausaha->where('tahun_lulus', $tahunLulus);
        }

        // ════════════════════════════════════════════════
        // SUMMARY METRICS
        // ════════════════════════════════════════════════
        $totalAlumniKuliah = (clone $queryAlumni)->count();
        $totalAlumniWirausaha = (clone $queryWirausaha)->count();
        $totalUniversitas = (clone $queryAlumni)->distinct('universitas_id')->count('universitas_id');
        $totalProgramStudi = (clone $queryAlumni)->distinct('program_studi')->count('program_studi');

        // ════════════════════════════════════════════════
        // STATISTICS
        // ════════════════════════════════════════════════

        // Universitas Terbanyak (Top 8)
        $universitasStats = (clone $queryAlumni)
            ->selectRaw('universitas_id, COUNT(*) as total')
            ->with('universitas')
            ->groupBy('universitas_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Program Studi Terbanyak (Top 8)
        $programStudiStats = (clone $queryAlumni)
            ->selectRaw('program_studi, COUNT(*) as total')
            ->groupBy('program_studi')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Bidang Usaha Terbanyak (Top 8)
        $bidangUsahaStats = (clone $queryWirausaha)
            ->selectRaw('bidang_usaha, COUNT(*) as total')
            ->groupBy('bidang_usaha')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Status Alumni
        $statusStats = [
            'Aktif' => (clone $queryAlumni)->where('status_alumni', 'aktif')->count(),
            'Lulus' => (clone $queryAlumni)->where('status_alumni', 'lulus')->count(),
            'Cuti' => (clone $queryAlumni)->where('status_alumni', 'cuti')->count(),
            'Belum Terdata' => (clone $queryAlumni)->where('status_alumni', 'belum_terdata')->count(),
        ];

        // ════════════════════════════════════════════════
        // DETAILED LISTS
        // ════════════════════════════════════════════════

        // Alumni Kuliah List
        $alumniList = (clone $queryAlumni)
            ->with('universitas')
            ->orderBy('nama_alumni')
            ->paginate(10, ['*'], 'page_kuliah')
            ->withQueryString();

        // Alumni Wirausaha List
        $alumniWirausahaList = (clone $queryWirausaha)
            ->orderBy('nama_alumni')
            ->paginate(10, ['*'], 'page_wirausaha')
            ->withQueryString();

        // Dropdown tahun lulus
        $tahunLulusList = array_unique(array_merge(
            AlumniKuliah::distinct()->pluck('tahun_lulus')->toArray(),
            AlumniWirausaha::distinct()->pluck('tahun_lulus')->toArray()
        ));
        rsort($tahunLulusList);

        return view('pages.bk.laporan.index', compact(
            'tahunLulus',
            'tahunLulusList',
            'totalAlumniKuliah',
            'totalAlumniWirausaha',
            'totalUniversitas',
            'totalProgramStudi',
            'universitasStats',
            'programStudiStats',
            'bidangUsahaStats',
            'statusStats',
            'alumniList',
            'alumniWirausahaList'
        ));
    }
}
