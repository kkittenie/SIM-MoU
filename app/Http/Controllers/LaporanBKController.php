<?php

namespace App\Http\Controllers;

use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\TracerKuliah;
use App\Models\Universitas;
use Illuminate\Http\Request;

class LaporanBKController extends Controller
{
    /**
     * Main laporan index - Alumni Kuliah & Tracer Study
     */
    public function index(Request $request)
    {
        $tahunLulus = $request->get('tahun_lulus');

        // Query base
        $queryAlumni = AlumniKuliah::query();
        $queryTracer = TracerKuliah::query();
        $queryWirausaha = AlumniWirausaha::query();

        // Filter by tahun lulus
        if ($tahunLulus) {
            $queryAlumni->where('tahun_lulus', $tahunLulus);
            $queryWirausaha->where('tahun_lulus', $tahunLulus);
            $queryTracer->whereHas('alumniKuliah', function ($q) use ($tahunLulus) {
                $q->where('tahun_lulus', $tahunLulus);
            });
        }

        // ════════════════════════════════════════════════
        // SUMMARY METRICS
        // ════════════════════════════════════════════════
        $totalAlumniKuliah = (clone $queryAlumni)->count();
        $totalAlumniWirausaha = (clone $queryWirausaha)->count();
        $totalUniversitas = (clone $queryAlumni)->distinct('universitas_id')->count('universitas_id');
        $totalTracerKuliah = (clone $queryTracer)->count();
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

        // Tracer Study Status
        $tracerStatusStats = (clone $queryTracer)
            ->selectRaw('status_kuliah, COUNT(*) as total')
            ->groupBy('status_kuliah')
            ->get()
            ->pluck('total', 'status_kuliah')
            ->toArray();

        // ════════════════════════════════════════════════
        // DETAILED LISTS
        // ════════════════════════════════════════════════

        // Alumni Kuliah List
        $alumniList = (clone $queryAlumni)
            ->with('universitas')
            ->orderBy('nama_alumni')
            ->get();

        // Alumni Wirausaha List
        $alumniWirausahaList = (clone $queryWirausaha)
            ->orderBy('nama_alumni')
            ->get();

        // Tracer Kuliah List (top 15)
        $tracerList = (clone $queryTracer)
            ->with('alumniKuliah')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

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
            'totalTracerKuliah',
            'totalProgramStudi',
            'universitasStats',
            'programStudiStats',
            'bidangUsahaStats',
            'statusStats',
            'tracerStatusStats',
            'alumniList',
            'alumniWirausahaList',
            'tracerList'
        ));
    }
}
