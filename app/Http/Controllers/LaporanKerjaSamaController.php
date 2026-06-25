<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use App\Models\KategoriMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKerjaSamaController extends Controller
{
    /**
     * Tampilkan halaman laporan Kerja Sama.
     */
    public function index(Request $request)
    {
        $year = $request->input('year');

        // Query dasar
        $query = KerjaSama::query();

        if ($year) {
            $query->whereYear('tanggal_mulai', $year);
        }

        // Hitung metrik ringkasan
        $today = Carbon::today();
        $thirtyDaysLater = Carbon::today()->addDays(30);

        $totalKerjaSama = (clone $query)->count();
        $totalAktif = (clone $query)->where('tanggal_berakhir', '>', $thirtyDaysLater)->count();
        $totalAkanBerakhir = (clone $query)->whereBetween('tanggal_berakhir', [$today, $thirtyDaysLater])->count();
        $totalExpired = (clone $query)->where('tanggal_berakhir', '<', $today)->count();

        // 1. Kategori Mitra Stats (distribusi per jenis_mitra)
        $kategoriStats = (clone $query)->select('jenis_mitra', DB::raw('count(*) as total'))
            ->groupBy('jenis_mitra')
            ->orderBy('total', 'desc')
            ->get();

        // 2. Status Stats (Aktif, Akan Berakhir, Berakhir)
        $statusStats = [
            'Aktif' => $totalAktif,
            'Akan Berakhir' => $totalAkanBerakhir,
            'Berakhir' => $totalExpired,
        ];

        // 3. Tren Kerja Sama per Tahun
        $yearExpr = DB::connection()->getDriverName() === 'sqlite' 
            ? "strftime('%Y', tanggal_mulai) as year" 
            : "YEAR(tanggal_mulai) as year";

        $trenStats = (clone $query)->selectRaw($yearExpr . ", count(*) as total")
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        // Ambil list tahun untuk dropdown filter (berdasarkan tanggal_mulai dari seluruh data)
        $yearsListExpr = DB::connection()->getDriverName() === 'sqlite' 
            ? "strftime('%Y', tanggal_mulai) as year" 
            : "YEAR(tanggal_mulai) as year";
            
        $tahunList = KerjaSama::selectRaw($yearsListExpr)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();
        sort($tahunList);

        // Ambil semua data untuk tabel detail
        $kerjaSamaList = (clone $query)->orderBy('tanggal_mulai', 'desc')->get();

        return view('pages.laporan.kerja-sama', compact(
            'totalKerjaSama',
            'totalAktif',
            'totalAkanBerakhir',
            'totalExpired',
            'kategoriStats',
            'statusStats',
            'trenStats',
            'tahunList',
            'year',
            'kerjaSamaList'
        ));
    }
}
