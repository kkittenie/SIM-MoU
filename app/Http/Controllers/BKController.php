<?php

namespace App\Http\Controllers;

use App\Models\AlumniKuliah;
use App\Models\Universitas;
use App\Models\TracerKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BKController extends Controller
{
    public function dashboard()
    {
        // Total alumni kuliah
        $totalAlumni = AlumniKuliah::count();

        // Alumni berdasarkan status
        $alumniAktif = AlumniKuliah::where('status_alumni', 'aktif')->count();
        $alumniLulus = AlumniKuliah::where('status_alumni', 'lulus')->count();
        $alumniCuti = AlumniKuliah::where('status_alumni', 'cuti')->count();
        $alumniPutus = AlumniKuliah::where('status_alumni', 'putus')->count();

        // Total universitas
        $totalUniversitas = Universitas::count();

        // Status distribution percentage
        $statusDistribution = [
            'aktif' => $totalAlumni > 0 ? round(($alumniAktif / $totalAlumni) * 100, 1) : 0,
            'lulus' => $totalAlumni > 0 ? round(($alumniLulus / $totalAlumni) * 100, 1) : 0,
            'cuti' => $totalAlumni > 0 ? round(($alumniCuti / $totalAlumni) * 100, 1) : 0,
            'putus' => $totalAlumni > 0 ? round(($alumniPutus / $totalAlumni) * 100, 1) : 0,
        ];

        // Recent activities (latest alumni registered)
        $recentAlumni = AlumniKuliah::latest()
            ->limit(3)
            ->get();

        // Alumni per universitas (top 5)
        $alumniPerUniversitas = AlumniKuliah::select('universitas_id', 'universitas.nama_universitas')
            ->join('universitas', 'alumni_kuliah.universitas_id', '=', 'universitas.id')
            ->groupBy('universitas_id', 'universitas.nama_universitas')
            ->selectRaw('count(*) as total')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('pages.bk.dashboard', compact(
            'totalAlumni',
            'alumniAktif',
            'alumniLulus',
            'alumniCuti',
            'alumniPutus',
            'totalUniversitas',
            'statusDistribution',
            'recentAlumni',
            'alumniPerUniversitas'
        ));
    }
}
