<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KerjaSama;
use App\Models\PerusahaanMitra;
use App\Models\AlumniBekerja;
use App\Models\LowonganKerja;
use App\Models\TracerStudy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama dengan metrik ringkasan.
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->isBKK()) {
            return $this->bkkDashboard();
        }

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
            'labels' => ['Aktif', 'Akan Berakhir', 'Berakhir']
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

    /**
     * Tampilkan dashboard khusus BKK.
     */
    private function bkkDashboard()
    {
        $totalAlumniBekerja = AlumniBekerja::count();
        $totalPerusahaanMitra = PerusahaanMitra::count();
        $totalLowonganKerja = LowonganKerja::where('status', 'Aktif')->count();
        $totalTracerStudy = TracerStudy::count();

        // 1. Grafik Persentase Alumni Bekerja (Tracer Study)
        $tracerStats = [
            'Bekerja' => TracerStudy::where('status_alumni', 'Bekerja')->count(),
            'Kuliah' => TracerStudy::where('status_alumni', 'Kuliah')->count(),
            'Wirausaha' => TracerStudy::where('status_alumni', 'Wirausaha')->count(),
            'Mencari Kerja' => TracerStudy::where('status_alumni', 'Mencari Kerja')->count(),
        ];

        $statusChartData = [
            'labels' => array_keys($tracerStats),
            'series' => array_values($tracerStats),
        ];

        // 2. Grafik Perusahaan yang Paling Banyak Merekrut (Top 5)
        $perusahaanRecruits = AlumniBekerja::select('perusahaan_nama', \DB::raw('count(*) as total'))
            ->groupBy('perusahaan_nama')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $perusahaanChartData = [
            'labels' => $perusahaanRecruits->pluck('perusahaan_nama')->toArray(),
            'series' => $perusahaanRecruits->pluck('total')->toArray(),
        ];

        // Fill empty chart defaults
        if (empty($perusahaanChartData['labels'])) {
            $perusahaanChartData['labels'] = ['Belum ada data'];
            $perusahaanChartData['series'] = [0];
        }

        // 3. Grafik Bidang Industri Terbanyak
        $industriStats = AlumniBekerja::select('bidang_industri', \DB::raw('count(*) as total'))
            ->groupBy('bidang_industri')
            ->orderBy('total', 'desc')
            ->get();

        $industriChartData = [
            'labels' => $industriStats->pluck('bidang_industri')->toArray(),
            'series' => $industriStats->pluck('total')->toArray(),
        ];

        if (empty($industriChartData['labels'])) {
            $industriChartData['labels'] = ['Belum ada data'];
            $industriChartData['series'] = [0];
        }

        // 4. Grafik Penyerapan Alumni per Tahun (Berdasarkan Tahun Lulus)
        $penyerapanStats = AlumniBekerja::select('tahun_lulus', \DB::raw('count(*) as total'))
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus', 'asc')
            ->get();

        $penyerapanChartData = [
            'labels' => $penyerapanStats->pluck('tahun_lulus')->toArray(),
            'series' => $penyerapanStats->pluck('total')->toArray(),
        ];

        if (empty($penyerapanChartData['labels'])) {
            $penyerapanChartData['labels'] = [date('Y')];
            $penyerapanChartData['series'] = [0];
        }

        // Insights BKK
        $insights = [];

        // Insight 1: Tingkat Keterserapan
        if ($totalTracerStudy > 0) {
            $bekerjaOrWirausaha = $tracerStats['Bekerja'] + $tracerStats['Wirausaha'];
            $rate = ($bekerjaOrWirausaha / $totalTracerStudy) * 100;
            $insights[] = "Tingkat keterserapan kerja & wirausaha alumni mencapai " . number_format($rate, 1) . "% (" . $bekerjaOrWirausaha . " dari " . $totalTracerStudy . " responden).";
        } else {
            $insights[] = "Belum ada respon Tracer Study masuk.";
        }

        // Insight 2: Bidang Industri Terpopuler
        $topIndustri = $industriStats->first();
        if ($topIndustri) {
            $insights[] = "Bidang industri \"{$topIndustri->bidang_industri}\" menyerap alumni terbanyak ({$topIndustri->total} orang).";
        }

        // Insight 3: Mitra Utama
        $topPerusahaan = $perusahaanRecruits->first();
        if ($topPerusahaan) {
            $insights[] = "Mitra perekrut utama alumni saat ini adalah \"{$topPerusahaan->perusahaan_nama}\" dengan total {$topPerusahaan->total} alumni.";
        }

        return view('pages.dashboard.bkk', compact(
            'totalAlumniBekerja',
            'totalPerusahaanMitra',
            'totalLowonganKerja',
            'totalTracerStudy',
            'statusChartData',
            'perusahaanChartData',
            'industriChartData',
            'penyerapanChartData',
            'insights'
        ));
    }
}
