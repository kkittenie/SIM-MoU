<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KerjaSama;
use App\Models\PerusahaanMitra;
use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\LowonganKerja;
use App\Models\Universitas;
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
        if (auth()->check() && auth()->user()->isBK()) {
            return $this->bkDashboard();
        }
        if (auth()->check() && auth()->user()->isAdminJurusan()) {
            return $this->adminJurusanDashboard();
        }

        return $this->adminDashboard();
    }

    /**
     * Dashboard untuk Admin Jurusan (Fokus pada MoU)
     */
    private function adminJurusanDashboard()
    {
        $today = Carbon::today();
        $sixMonthsLater = Carbon::today()->addDays(183);

        $totalUsers = User::count();
        $totalKerjaSama = KerjaSama::count();

        // Hitung status MoU berdasarkan tanggal_berakhir
        $totalExpired = KerjaSama::where('tanggal_berakhir', '<', $today)->count();
        $totalAkanBerakhir = KerjaSama::whereBetween('tanggal_berakhir', [$today, $sixMonthsLater])->count();
        $totalAktif = KerjaSama::where('tanggal_berakhir', '>', $sixMonthsLater)->count();

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
            $insights[] = "Terdapat {$totalAkanBerakhir} MoU yang akan berakhir dalam 6 bulan.";
        } else {
            $insights[] = "Tidak ada MoU yang akan berakhir dalam 6 bulan ke depan.";
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

        return view('pages.admin_jurusan.dashboard', compact(
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
     * Dashboard untuk Super Admin (Summary Seluruh Fitur)
     */
    private function adminDashboard()
    {
        $today = Carbon::today();
        
        // 1. Total MoU
        $totalKerjaSama = KerjaSama::count();
        $totalMouAktif = KerjaSama::where('tanggal_berakhir', '>', $today->copy()->addDays(183))->count();
        $totalMouAkanBerakhir = KerjaSama::whereBetween('tanggal_berakhir', [$today, $today->copy()->addDays(183)])->count();
        
        // 2. Total BK (Kuliah & Wirausaha)
        $totalKuliah = AlumniKuliah::tahunAjaranAktif()->count();
        $totalWirausaha = AlumniWirausaha::tahunAjaranAktif()->count();
        
        // 3. Total BKK (Bekerja)
        $totalBekerja = AlumniBekerja::tahunAjaranAktif()->count();
        
        // 4. Statistik Notifikasi (Ringkasan)
        $totalNotifikasiBelumDibaca = \App\Models\Notification::where('is_read', false)->count();

        // Grafik Distribusi Alumni Total (Bekerja, Kuliah, Wirausaha)
        $alumniDistribData = [
            'labels' => ['Bekerja', 'Kuliah', 'Wirausaha'],
            'series' => [$totalBekerja, $totalKuliah, $totalWirausaha]
        ];

        // 5. Statistik MoU per Program Keahlian
        $mouPerJurusan = KerjaSama::select('program_keahlian_id', \DB::raw('count(*) as total'))
            ->with('programKeahlian')
            ->groupBy('program_keahlian_id')
            ->orderBy('total', 'desc')
            ->get();
            
        $jurusanChartData = [
            'labels' => $mouPerJurusan->map(function($ks) {
                return $ks->programKeahlian ? $ks->programKeahlian->nama : 'Semua Jurusan';
            })->toArray(),
            'series' => $mouPerJurusan->pluck('total')->toArray()
        ];
        
        if (empty($jurusanChartData['labels'])) {
            $jurusanChartData['labels'] = ['Belum ada data'];
            $jurusanChartData['series'] = [0];
        }

        // Insights untuk Admin
        $insights = [];
        $insights[] = "Total kerja sama saat ini mencapai <strong>{$totalKerjaSama} mitra</strong>.";
        if ($totalMouAkanBerakhir > 0) {
            $insights[] = "Terdapat <strong>{$totalMouAkanBerakhir} MoU</strong> yang akan segera berakhir (<= 6 bulan).";
        }
        $insights[] = "Penyerapan alumni tahun ajaran aktif: <strong>{$totalBekerja} bekerja</strong>, <strong>{$totalKuliah} kuliah</strong>, <strong>{$totalWirausaha} wirausaha</strong>.";
        if ($totalNotifikasiBelumDibaca > 0) {
            $insights[] = "Ada <strong>{$totalNotifikasiBelumDibaca} notifikasi</strong> baru yang belum Anda baca.";
        }

        return view('pages.admin.dashboard', compact(
            'totalKerjaSama',
            'totalMouAktif',
            'totalMouAkanBerakhir',
            'totalKuliah',
            'totalWirausaha',
            'totalBekerja',
            'totalNotifikasiBelumDibaca',
            'alumniDistribData',
            'jurusanChartData',
            'insights'
        ));
    }

    /**
     * Tampilkan dashboard khusus BKK.
     */
    private function bkkDashboard()
    {
        $totalAlumniBekerja = AlumniBekerja::tahunAjaranAktif()->count();
        $totalPerusahaanMitra = PerusahaanMitra::count();
        $totalLowonganKerja = LowonganKerja::where('status', 'Aktif')->count();

        // 1. Grafik Status Pekerjaan Alumni
        $statusStats = [
            'Tetap' => AlumniBekerja::tahunAjaranAktif()->where('status_pekerjaan', 'Tetap')->count(),
            'Kontrak' => AlumniBekerja::tahunAjaranAktif()->where('status_pekerjaan', 'Kontrak')->count(),
            'Freelance' => AlumniBekerja::tahunAjaranAktif()->where('status_pekerjaan', 'Freelance')->count(),
        ];

        $statusChartData = [
            'labels' => array_keys($statusStats),
            'series' => array_values($statusStats),
        ];

        // 2. Grafik Perusahaan yang Paling Banyak Merekrut (Top 5)
        $perusahaanRecruits = AlumniBekerja::tahunAjaranAktif()->select('perusahaan_nama', \DB::raw('count(*) as total'))
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
        $industriStats = AlumniBekerja::tahunAjaranAktif()->select('bidang_industri', \DB::raw('count(*) as total'))
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

        // Insight 1: Total Alumni Bekerja
        if ($totalAlumniBekerja > 0) {
            $insights[] = "Terdapat {$totalAlumniBekerja} alumni yang tercatat bekerja pada tahun ajaran aktif.";
        } else {
            $insights[] = "Belum ada data alumni bekerja pada tahun ajaran aktif.";
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

        return view('pages.bkk.dashboard', compact(
            'totalAlumniBekerja',
            'totalPerusahaanMitra',
            'totalLowonganKerja',
            'statusChartData',
            'perusahaanChartData',
            'industriChartData',
            'penyerapanChartData',
            'insights'
        ));
    }

    private function bkDashboard()
    {
        $totalAlumniKuliah = AlumniKuliah::tahunAjaranAktif()->count();
        $totalAlumniWirausaha = AlumniWirausaha::tahunAjaranAktif()->count();
        $totalUniversitas = Universitas::where('status', 'aktif')->count();

        // 1. Grafik Persentase Penyaluran BK (Kuliah vs Wirausaha) - Donut
        $distribChartData = [
            'series' => [$totalAlumniKuliah, $totalAlumniWirausaha],
            'labels' => ['Kuliah', 'Wirausaha']
        ];

        // 2. Grafik Distribusi Alumni per Universitas (Kampus Tujuan Terbanyak)
        $alumniPerUniversitas = AlumniKuliah::tahunAjaranAktif()->select('universitas_id')
            ->with('universitas')
            ->groupBy('universitas_id')
            ->selectRaw('universitas_id, count(*) as total')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        $universChartData = [
            'labels' => $alumniPerUniversitas->map(function ($item) {
                $name = $item->universitas->nama_universitas ?? 'Unknown';
                return strlen($name) > 25 ? substr($name, 0, 25) . '...' : $name;
            })->toArray(),
            'series' => $alumniPerUniversitas->pluck('total')->toArray()
        ];

        if (empty($universChartData['labels'])) {
            $universChartData['labels'] = ['Belum ada data'];
            $universChartData['series'] = [0];
        }

        // 3. Grafik Bidang Usaha Terbanyak
        $wirausahaStats = AlumniWirausaha::tahunAjaranAktif()->select('bidang_usaha', \DB::raw('count(*) as total'))
            ->groupBy('bidang_usaha')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get();

        $wirausahaChartData = [
            'labels' => $wirausahaStats->pluck('bidang_usaha')->toArray(),
            'series' => $wirausahaStats->pluck('total')->toArray()
        ];

        if (empty($wirausahaChartData['labels'])) {
            $wirausahaChartData['labels'] = ['Belum ada data'];
            $wirausahaChartData['series'] = [0];
        }

        // 4. Grafik Tren Alumni per Tahun Lulus (Area)
        $trendAlumni = AlumniKuliah::select('tahun_lulus')
            ->selectRaw('count(*) as total')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus', 'asc')
            ->get();

        $trendChartData = [
            'labels' => $trendAlumni->pluck('tahun_lulus')->toArray(),
            'series' => $trendAlumni->pluck('total')->toArray()
        ];

        if (empty($trendChartData['labels'])) {
            $trendChartData['labels'] = [date('Y')];
            $trendChartData['series'] = [0];
        }

        // 5. Dynamic Insights
        $insights = [];
        $insights[] = "Terdapat <strong>{$totalAlumniKuliah} alumni</strong> melanjutkan kuliah dan <strong>{$totalAlumniWirausaha} alumni</strong> yang berwirausaha.";

        if ($totalAlumniKuliah > 0) {
            $topUniv = $alumniPerUniversitas->first();
            if ($topUniv && $topUniv->universitas) {
                $univName = $topUniv->universitas->nama_universitas;
                $insights[] = "Perguruan tinggi terpopuler adalah <strong>{$univName}</strong> dengan {$topUniv->total} alumni.";
            }
        }

        if ($totalAlumniWirausaha > 0) {
            $topBidang = $wirausahaStats->first();
            if ($topBidang) {
                $insights[] = "Bidang usaha terpopuler adalah <strong>{$topBidang->bidang_usaha}</strong> dengan {$topBidang->total} alumni wirausaha.";
            }
        }

        return view('pages.bk.dashboard', compact(
            'totalAlumniKuliah',
            'totalAlumniWirausaha',
            'totalUniversitas',
            'distribChartData',
            'universChartData',
            'wirausahaChartData',
            'trendChartData',
            'insights'
        ));
    }
}
