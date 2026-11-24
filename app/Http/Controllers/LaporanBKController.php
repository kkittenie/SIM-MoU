<?php

namespace App\Http\Controllers;

use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\Universitas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    /**
     * Ekspor data laporan BK (Alumni Kuliah & Wirausaha) ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $tahunLulus = $request->input('tahun_lulus');

        // Query Alumni Kuliah
        $queryKuliah = AlumniKuliah::query()->with('universitas')->tahunAjaranAktif();
        if ($tahunLulus) {
            $queryKuliah->where('tahun_lulus', $tahunLulus);
        }
        $alumniKuliah = $queryKuliah->orderBy('tahun_lulus', 'desc')->latest()->get();

        // Query Alumni Wirausaha
        $queryWirausaha = AlumniWirausaha::query()->tahunAjaranAktif();
        if ($tahunLulus) {
            $queryWirausaha->where('tahun_lulus', $tahunLulus);
        }
        $alumniWirausaha = $queryWirausaha->orderBy('tahun_lulus', 'desc')->latest()->get();

        $spreadsheet = new Spreadsheet();

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

        $borderStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ];

        // ═════════════════════════════════════════════════════════════
        // SHEET 1: ALUMNI KULIAH
        // ═════════════════════════════════════════════════════════════
        $sheetKuliah = $spreadsheet->getActiveSheet();
        $sheetKuliah->setTitle('Alumni Kuliah');

        // Judul Laporan Kuliah
        $sheetKuliah->mergeCells('A1:L1');
        $sheetKuliah->setCellValue('A1', 'Laporan Data Alumni Kuliah (BK) SMKN 1 Cirebon');
        $sheetKuliah->getStyle('A1')->applyFromArray($titleStyle);
        $sheetKuliah->getRowDimension(1)->setRowHeight(22);

        // Subtitle / Info Filter Kuliah
        $sheetKuliah->mergeCells('A2:L2');
        $infoTextKuliah = 'Diekspor pada: ' . date('d F Y H:i:s');
        if ($tahunLulus) {
            $infoTextKuliah .= ' | Tahun Lulus: ' . $tahunLulus;
        } else {
            $infoTextKuliah .= ' | Semua Tahun Lulus';
        }
        $infoTextKuliah .= ' | Total Data Kuliah: ' . count($alumniKuliah);
        $sheetKuliah->setCellValue('A2', $infoTextKuliah);
        $sheetKuliah->getStyle('A2')->applyFromArray($infoStyle);
        $sheetKuliah->getRowDimension(2)->setRowHeight(16);
        $sheetKuliah->getRowDimension(3)->setRowHeight(8);

        // Lebar kolom Kuliah
        $sheetKuliah->getColumnDimension('A')->setWidth(6);   // No
        $sheetKuliah->getColumnDimension('B')->setWidth(16);  // NIS
        $sheetKuliah->getColumnDimension('C')->setWidth(26);  // Nama Alumni
        $sheetKuliah->getColumnDimension('D')->setWidth(26);  // Program Keahlian
        $sheetKuliah->getColumnDimension('E')->setWidth(14);  // Kelas
        $sheetKuliah->getColumnDimension('F')->setWidth(14);  // Tahun Lulus
        $sheetKuliah->getColumnDimension('G')->setWidth(30);  // Universitas
        $sheetKuliah->getColumnDimension('H')->setWidth(24);  // Program Studi
        $sheetKuliah->getColumnDimension('I')->setWidth(18);  // Jalur Masuk
        $sheetKuliah->getColumnDimension('J')->setWidth(16);  // Status
        $sheetKuliah->getColumnDimension('K')->setWidth(26);  // Email
        $sheetKuliah->getColumnDimension('L')->setWidth(18);  // No. WhatsApp

        // Header Kuliah
        $headersKuliah = [
            'No',
            'NIS',
            'Nama Alumni',
            'Program Keahlian',
            'Kelas',
            'Tahun Lulus',
            'Universitas / Kampus',
            'Program Studi',
            'Jalur Masuk',
            'Status Kuliah',
            'Email',
            'No. WhatsApp'
        ];
        $sheetKuliah->fromArray($headersKuliah, null, 'A4');
        $sheetKuliah->getStyle('A4:L4')->applyFromArray($headerStyle);
        $sheetKuliah->getRowDimension(4)->setRowHeight(22);

        $rowK = 5;
        foreach ($alumniKuliah as $index => $item) {
            $sheetKuliah->setCellValue('A' . $rowK, $index + 1);
            $sheetKuliah->setCellValueExplicit('B' . $rowK, (string) ($item->nis ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetKuliah->setCellValue('C' . $rowK, $item->nama_alumni ?? '-');
            $sheetKuliah->setCellValue('D' . $rowK, $item->program_keahlian ?? '-');
            $sheetKuliah->setCellValue('E' . $rowK, $item->kelas ?? '-');
            $sheetKuliah->setCellValue('F' . $rowK, $item->tahun_lulus ?? '-');
            $sheetKuliah->setCellValue('G' . $rowK, $item->universitas?->nama_universitas ?? '-');
            $sheetKuliah->setCellValue('H' . $rowK, $item->program_studi ?? '-');
            $sheetKuliah->setCellValue('I' . $rowK, $item->cara_masuk_label ?? '-');
            $sheetKuliah->setCellValue('J' . $rowK, ucfirst($item->status_alumni ?? '-'));
            $sheetKuliah->setCellValue('K' . $rowK, $item->email_alumni ?? '-');
            $sheetKuliah->setCellValueExplicit('L' . $rowK, (string) ($item->no_wa ?? $item->nomor_telepon ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $rowStyle = [
                'font' => [
                    'size' => 10,
                    'name' => 'Times New Roman',
                ],
                'border' => $borderStyle,
            ];
            $sheetKuliah->getStyle('A' . $rowK . ':L' . $rowK)->applyFromArray($rowStyle);
            $sheetKuliah->getRowDimension($rowK)->setRowHeight(18);

            $sheetKuliah->getStyle('A' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('B' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('E' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('F' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('I' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('J' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetKuliah->getStyle('L' . $rowK)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowK++;
        }

        $sheetKuliah->freezePane('A5');
        $sheetKuliah->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheetKuliah->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheetKuliah->getPageMargins()->setLeft(0.5);
        $sheetKuliah->getPageMargins()->setRight(0.5);
        $sheetKuliah->getPageMargins()->setTop(0.75);
        $sheetKuliah->getPageMargins()->setBottom(0.75);

        // ═════════════════════════════════════════════════════════════
        // SHEET 2: ALUMNI WIRAUSAHA
        // ═════════════════════════════════════════════════════════════
        $sheetWirausaha = $spreadsheet->createSheet();
        $sheetWirausaha->setTitle('Alumni Wirausaha');

        // Judul Laporan Wirausaha
        $sheetWirausaha->mergeCells('A1:J1');
        $sheetWirausaha->setCellValue('A1', 'Laporan Data Alumni Wirausaha (BK) SMKN 1 Cirebon');
        $sheetWirausaha->getStyle('A1')->applyFromArray($titleStyle);
        $sheetWirausaha->getRowDimension(1)->setRowHeight(22);

        // Subtitle / Info Filter Wirausaha
        $sheetWirausaha->mergeCells('A2:J2');
        $infoTextWirausaha = 'Diekspor pada: ' . date('d F Y H:i:s');
        if ($tahunLulus) {
            $infoTextWirausaha .= ' | Tahun Lulus: ' . $tahunLulus;
        } else {
            $infoTextWirausaha .= ' | Semua Tahun Lulus';
        }
        $infoTextWirausaha .= ' | Total Data Wirausaha: ' . count($alumniWirausaha);
        $sheetWirausaha->setCellValue('A2', $infoTextWirausaha);
        $sheetWirausaha->getStyle('A2')->applyFromArray($infoStyle);
        $sheetWirausaha->getRowDimension(2)->setRowHeight(16);
        $sheetWirausaha->getRowDimension(3)->setRowHeight(8);

        // Lebar kolom Wirausaha
        $sheetWirausaha->getColumnDimension('A')->setWidth(6);   // No
        $sheetWirausaha->getColumnDimension('B')->setWidth(16);  // NIS
        $sheetWirausaha->getColumnDimension('C')->setWidth(26);  // Nama Alumni
        $sheetWirausaha->getColumnDimension('D')->setWidth(26);  // Program Keahlian
        $sheetWirausaha->getColumnDimension('E')->setWidth(14);  // Kelas
        $sheetWirausaha->getColumnDimension('F')->setWidth(14);  // Tahun Lulus
        $sheetWirausaha->getColumnDimension('G')->setWidth(28);  // Nama Usaha
        $sheetWirausaha->getColumnDimension('H')->setWidth(24);  // Bidang Usaha
        $sheetWirausaha->getColumnDimension('I')->setWidth(18);  // Lama Usaha
        $sheetWirausaha->getColumnDimension('J')->setWidth(18);  // No. WhatsApp

        // Header Wirausaha
        $headersWirausaha = [
            'No',
            'NIS',
            'Nama Alumni',
            'Program Keahlian',
            'Kelas',
            'Tahun Lulus',
            'Nama Usaha / Bisnis',
            'Bidang Usaha',
            'Lama Usaha',
            'No. WhatsApp'
        ];
        $sheetWirausaha->fromArray($headersWirausaha, null, 'A4');
        $sheetWirausaha->getStyle('A4:J4')->applyFromArray($headerStyle);
        $sheetWirausaha->getRowDimension(4)->setRowHeight(22);

        $rowW = 5;
        foreach ($alumniWirausaha as $index => $item) {
            $sheetWirausaha->setCellValue('A' . $rowW, $index + 1);
            $sheetWirausaha->setCellValueExplicit('B' . $rowW, (string) ($item->nis ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetWirausaha->setCellValue('C' . $rowW, $item->nama_alumni ?? '-');
            $sheetWirausaha->setCellValue('D' . $rowW, $item->program_keahlian ?? '-');
            $sheetWirausaha->setCellValue('E' . $rowW, $item->kelas ?? '-');
            $sheetWirausaha->setCellValue('F' . $rowW, $item->tahun_lulus ?? '-');
            $sheetWirausaha->setCellValue('G' . $rowW, $item->nama_usaha ?? '-');
            $sheetWirausaha->setCellValue('H' . $rowW, $item->bidang_usaha ?? '-');
            $sheetWirausaha->setCellValue('I' . $rowW, $item->lama_usaha ?? '-');
            $sheetWirausaha->setCellValueExplicit('J' . $rowW, (string) ($item->no_wa ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $rowStyle = [
                'font' => [
                    'size' => 10,
                    'name' => 'Times New Roman',
                ],
                'border' => $borderStyle,
            ];
            $sheetWirausaha->getStyle('A' . $rowW . ':J' . $rowW)->applyFromArray($rowStyle);
            $sheetWirausaha->getRowDimension($rowW)->setRowHeight(18);

            $sheetWirausaha->getStyle('A' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetWirausaha->getStyle('B' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetWirausaha->getStyle('E' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetWirausaha->getStyle('F' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetWirausaha->getStyle('I' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetWirausaha->getStyle('J' . $rowW)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowW++;
        }

        $sheetWirausaha->freezePane('A5');
        $sheetWirausaha->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheetWirausaha->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheetWirausaha->getPageMargins()->setLeft(0.5);
        $sheetWirausaha->getPageMargins()->setRight(0.5);
        $sheetWirausaha->getPageMargins()->setTop(0.75);
        $sheetWirausaha->getPageMargins()->setBottom(0.75);

        // Kembalikan active sheet ke sheet pertama (Alumni Kuliah)
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'laporan-bk-alumni-kuliah-wirausaha-' . date('Y-m-d-His') . '.xlsx';

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
