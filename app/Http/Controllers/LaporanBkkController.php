<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\PerusahaanMitra;
use App\Models\LowonganKerja;
use App\Models\ProgramKeahlian;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanBkkController extends Controller
{
    /**
     * Tampilkan halaman laporan BKK.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tahunLulus = $request->has('tahun_lulus') ? $request->input('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();
        $selectedProgramKeahlian = null;

        if ($user && $user->isAdminJurusan()) {
            $programKeahlianId = $user->getAssignedProgramKeahlianId();
            $selectedProgramKeahlian = $user->programKeahlian ?: ProgramKeahlian::find($programKeahlianId);
        } else {
            $programKeahlianId = $request->input('program_keahlian_id');
            $selectedProgramKeahlian = $programKeahlianId ? ProgramKeahlian::find($programKeahlianId) : null;
        }

        // Metrik Ringkasan
        $queryAlumni = AlumniBekerja::query()->tahunAjaranAktif();

        if ($tahunLulus) {
            $queryAlumni->where('tahun_lulus', $tahunLulus);
        }

        if ($selectedProgramKeahlian) {
            $deptNames = array_filter(array_unique([$selectedProgramKeahlian->nama, $selectedProgramKeahlian->singkatan]));
            $queryAlumni->where(function ($q) use ($deptNames, $selectedProgramKeahlian) {
                $q->whereIn('program_keahlian', $deptNames)
                  ->orWhereIn('nis', function ($sub) use ($selectedProgramKeahlian) {
                      $sub->select('nis')->from('student_nis')->where('program_keahlian_id', $selectedProgramKeahlian->id);
                  });
            });
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
        $tahunQuery = AlumniBekerja::query();
        if ($selectedProgramKeahlian) {
            $deptNames = array_filter(array_unique([$selectedProgramKeahlian->nama, $selectedProgramKeahlian->singkatan]));
            $tahunQuery->where(function ($q) use ($deptNames, $selectedProgramKeahlian) {
                $q->whereIn('program_keahlian', $deptNames)
                  ->orWhereIn('nis', function ($sub) use ($selectedProgramKeahlian) {
                      $sub->select('nis')->from('student_nis')->where('program_keahlian_id', $selectedProgramKeahlian->id);
                  });
            });
        }
        $tahunLulusList = $tahunQuery->distinct()->pluck('tahun_lulus')->filter()->sort()->toArray();

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
            'programKeahlianId',
            'selectedProgramKeahlian',
            'programKeahlians',
            'alumniList'
        ));
    }

    /**
     * Ekspor data laporan alumni bekerja ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $tahunLulus = $request->input('tahun_lulus');

        if ($user && $user->isAdminJurusan()) {
            $programKeahlianId = $user->getAssignedProgramKeahlianId();
        } else {
            $programKeahlianId = $request->input('program_keahlian_id');
        }

        $selectedProgramKeahlian = $programKeahlianId ? ProgramKeahlian::find($programKeahlianId) : null;

        $query = AlumniBekerja::query()->with('perusahaanMitra')->tahunAjaranAktif();
        if ($tahunLulus) {
            $query->where('tahun_lulus', $tahunLulus);
        }

        if ($selectedProgramKeahlian) {
            $deptNames = array_filter(array_unique([$selectedProgramKeahlian->nama, $selectedProgramKeahlian->singkatan]));
            $query->where(function ($q) use ($deptNames, $selectedProgramKeahlian) {
                $q->whereIn('program_keahlian', $deptNames)
                  ->orWhereIn('nis', function ($sub) use ($selectedProgramKeahlian) {
                      $sub->select('nis')->from('student_nis')->where('program_keahlian_id', $selectedProgramKeahlian->id);
                  });
            });
        }

        $alumni = $query->orderBy('tahun_lulus', 'desc')->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Alumni Bekerja');

        // Judul Laporan
        $sheet->mergeCells('A1:M1');
        $judulLaporan = 'Laporan Data Alumni Bekerja (BKK) SMKN 1 Cirebon';
        if ($selectedProgramKeahlian) {
            $judulLaporan .= ' - ' . $selectedProgramKeahlian->nama;
        }
        $sheet->setCellValue('A1', $judulLaporan);

        $titleStyle = [
            'font' => [
                'bold' => true,
                'size' => 13,
                'name' => 'Times New Roman',
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Subtitle / Info Filter
        $sheet->mergeCells('A2:M2');
        $infoText = 'Diekspor pada: ' . date('d F Y H:i:s');
        if ($selectedProgramKeahlian) {
            $infoText .= ' | Jurusan: ' . $selectedProgramKeahlian->nama;
        } else {
            $infoText .= ' | Semua Jurusan';
        }
        if ($tahunLulus) {
            $infoText .= ' | Tahun Lulus: ' . $tahunLulus;
        } else {
            $infoText .= ' | Semua Tahun Lulus';
        }
        $infoText .= ' | Total Data: ' . count($alumni);
        $sheet->setCellValue('A2', $infoText);

        $infoStyle = [
            'font' => [
                'size' => 9,
                'name' => 'Times New Roman',
                'color' => ['rgb' => '666666'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2')->applyFromArray($infoStyle);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(8);

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(16);  // NIS
        $sheet->getColumnDimension('C')->setWidth(26);  // Nama Alumni
        $sheet->getColumnDimension('D')->setWidth(26);  // Program Keahlian
        $sheet->getColumnDimension('E')->setWidth(14);  // Kelas
        $sheet->getColumnDimension('F')->setWidth(14);  // Tahun Lulus
        $sheet->getColumnDimension('G')->setWidth(28);  // Perusahaan
        $sheet->getColumnDimension('H')->setWidth(22);  // Bidang Industri
        $sheet->getColumnDimension('I')->setWidth(22);  // Jabatan
        $sheet->getColumnDimension('J')->setWidth(16);  // Status Pekerjaan
        $sheet->getColumnDimension('K')->setWidth(16);  // Tanggal Masuk
        $sheet->getColumnDimension('L')->setWidth(22);  // Lokasi Kerja
        $sheet->getColumnDimension('M')->setWidth(18);  // Gaji

        // Header Tabel
        $headers = [
            'No',
            'NIS',
            'Nama Alumni',
            'Program Keahlian',
            'Kelas',
            'Tahun Lulus',
            'Perusahaan / Instansi',
            'Bidang Industri',
            'Jabatan',
            'Status Pekerjaan',
            'Tanggal Masuk',
            'Lokasi Kerja',
            'Estimasi Gaji'
        ];
        $sheet->fromArray($headers, null, 'A4');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 10,
                'name' => 'Times New Roman',
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E5E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'border' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '999999'],
                ],
            ],
        ];
        $sheet->getStyle('A4:M4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(22);

        $row = 5;
        $borderStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ];

        foreach ($alumni as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValueExplicit('B' . $row, (string) ($item->nis ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $item->nama_alumni ?? '-');
            $sheet->setCellValue('D' . $row, $item->program_keahlian ?? '-');
            $sheet->setCellValue('E' . $row, $item->kelas ?? '-');
            $sheet->setCellValue('F' . $row, $item->tahun_lulus ?? '-');
            $sheet->setCellValue('G' . $row, $item->nama_perusahaan);
            $sheet->setCellValue('H' . $row, $item->bidang_industri ?? '-');
            $sheet->setCellValue('I' . $row, $item->jabatan ?? '-');
            $sheet->setCellValue('J' . $row, ucfirst($item->status_pekerjaan ?? '-'));
            $sheet->setCellValue('K' . $row, $item->tanggal_masuk ? $item->tanggal_masuk->format('d-m-Y') : '-');
            $sheet->setCellValue('L' . $row, $item->lokasi_kerja ?? '-');
            $sheet->setCellValue('M' . $row, $item->gaji ? 'Rp ' . number_format($item->gaji, 0, ',', '.') : '-');

            $rowStyle = [
                'font' => [
                    'size' => 10,
                    'name' => 'Times New Roman',
                ],
                'border' => $borderStyle,
            ];

            $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray($rowStyle);
            $sheet->getRowDimension($row)->setRowHeight(18);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $row++;
        }

        $sheet->freezePane('A5');
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setBottom(0.75);

        $filename = 'laporan-bkk-alumni-bekerja-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
