<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KerjaSama;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama dengan metrik ringkasan.
     */
    public function index()
    {
        $today = Carbon::today();
        $thirtyDaysLater = Carbon::today()->addDays(30);

        $totalUsers = User::count();
        $totalKerjaSama = KerjaSama::count();

        // Hitung status MoU berdasarkan tanggal_berakhir
        $totalExpired = KerjaSama::where('tanggal_berakhir', '<', $today)->count();
        $totalAkanBerakhir = KerjaSama::whereBetween('tanggal_berakhir', [$today, $thirtyDaysLater])->count();
        $totalAktif = KerjaSama::where('tanggal_berakhir', '>', $thirtyDaysLater)->count();

        // 1. Grafik Status MoU
        $statusChartData = [
            'series' => [$totalAktif, $totalAkanBerakhir, $totalExpired],
            'labels' => ['Aktif', 'Akan Berakhir', 'Expired']
        ];

        // 2. Grafik Jenis Mitra
        $categories = \App\Models\KategoriMitra::pluck('nama')->toArray();
        $jenisMitraCounts = [];
        foreach ($categories as $cat) {
            $jenisMitraCounts[$cat] = KerjaSama::where('jenis_mitra', $cat)->count();
        }

        $jenisChartData = [
            'labels' => array_keys($jenisMitraCounts),
            'series' => array_values($jenisMitraCounts)
        ];

        // 3. Grafik Tren Kerja Sama (per tahun)
        $yearExpression = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite' 
            ? "strftime('%Y', tanggal_mulai) as tahun" 
            : "YEAR(tanggal_mulai) as tahun";

        $trenKerjaSama = KerjaSama::selectRaw($yearExpression . ', count(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $trendChartData = [
            'labels' => $trenKerjaSama->pluck('tahun')->toArray(),
            'series' => $trenKerjaSama->pluck('total')->toArray()
        ];

        // Handle empty trends case to prevent empty chart errors
        if (empty($trendChartData['labels'])) {
            $trendChartData['labels'] = [date('Y')];
            $trendChartData['series'] = [0];
        }

        // 4. Grafik MoU Akan Berakhir (Sisa hari terdekat)
        $nearestExpiring = KerjaSama::where('tanggal_berakhir', '>=', $today)
            ->orderBy('tanggal_berakhir', 'asc')
            ->limit(5)
            ->get();

        $nearestChartData = [
            'labels' => $nearestExpiring->map(function ($item) {
                $name = $item->nama_mitra;
                return strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;
            })->toArray(),
            'series' => $nearestExpiring->map(function ($item) use ($today) {
                return $today->diffInDays(Carbon::parse($item->tanggal_berakhir), false);
            })->toArray()
        ];

        // 5. Dynamic Insights
        $insights = [];

        // Insight 1: Expiration
        if ($totalAkanBerakhir > 0) {
            $insights[] = "Terdapat {$totalAkanBerakhir} MoU yang akan berakhir dalam 30 hari.";
        } else {
            $insights[] = "Tidak ada MoU yang akan berakhir dalam 30 hari ke depan.";
        }

        // Insight 2: Category
        $minCategory = null;
        $minVal = 999999;
        foreach ($jenisMitraCounts as $cat => $cnt) {
            if ($cnt < $minVal) {
                $minVal = $cnt;
                $minCategory = $cat;
            }
        }
        if ($minCategory) {
            $insights[] = "Kerja sama dengan {$minCategory} masih rendah ({$minVal} mitra) dibanding kategori lainnya.";
        }

        // Insight 3: YoY Trend
        $currentYear = date('Y');
        $lastYear = $currentYear - 1;
        $countThisYear = KerjaSama::whereYear('tanggal_mulai', $currentYear)->count();
        $countLastYear = KerjaSama::whereYear('tanggal_mulai', $lastYear)->count();

        if ($countThisYear > $countLastYear) {
            $insights[] = "Jumlah kerja sama baru tahun ini ({$countThisYear}) meningkat dibanding tahun sebelumnya ({$countLastYear}).";
        } elseif ($countThisYear < $countLastYear) {
            $insights[] = "Jumlah kerja sama baru tahun ini ({$countThisYear}) menurun dibanding tahun sebelumnya ({$countLastYear}).";
        } else {
            $insights[] = "Jumlah kerja sama baru tahun ini ({$countThisYear}) stabil dibanding tahun sebelumnya ({$countLastYear}).";
        }

        return view('pages.dashboard', compact(
            'totalUsers',
            'totalKerjaSama',
            'totalExpired',
            'totalAkanBerakhir',
            'totalAktif',
            'statusChartData',
            'jenisChartData',
            'trendChartData',
            'nearestChartData',
            'insights'
        ));
    }
}
