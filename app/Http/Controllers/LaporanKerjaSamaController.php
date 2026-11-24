<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use App\Models\KategoriMitra;
use App\Models\ProgramKeahlian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanKerjaSamaController extends Controller
{
    /**
     * Tampilkan halaman laporan Kerja Sama.
     */
    public function index(Request $request)
    {
        $year = $request->input('year');
        $status = $request->input('status');
        $jenisMitra = $request->input('jenis_mitra');
        $programKeahlianId = $request->input('program_keahlian_id');
        $search = $request->input('search');

        // Query dasar
        $query = KerjaSama::with(['kategoriMitra', 'programKeahlian']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                  ->orWhere('nomor_mou', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
            });
        }

        if ($jenisMitra) {
            $query->where('jenis_mitra', $jenisMitra);
        }

        if ($programKeahlianId) {
            $query->where('program_keahlian_id', $programKeahlianId);
        }

        $today = Carbon::today();
        $sixMonthsLater = Carbon::today()->addDays(183);

        if ($status) {
            if ($status === 'Berakhir') {
                $query->where('tanggal_berakhir', '<', $today);
            } elseif ($status === 'Akan Berakhir') {
                $query->whereBetween('tanggal_berakhir', [$today, $sixMonthsLater]);
            } elseif ($status === 'Aktif') {
                $query->where('tanggal_berakhir', '>', $sixMonthsLater);
            }
        }

        if ($year) {
            $query->whereYear('tanggal_mulai', $year);
        }

        // Hitung metrik ringkasan
        $totalKerjaSama = (clone $query)->count();
        $totalAktif = (clone $query)->where('tanggal_berakhir', '>', $sixMonthsLater)->count();
        $totalAkanBerakhir = (clone $query)->whereBetween('tanggal_berakhir', [$today, $sixMonthsLater])->count();
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
        $kerjaSamaList = (clone $query)->orderBy('tanggal_mulai', 'desc')->paginate(10)->withQueryString();

        $categories = KategoriMitra::orderBy('nama', 'asc')->get();
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        $ptSuggestions = KerjaSama::distinct()->pluck('nama_mitra')
            ->merge(\App\Models\PerusahaanMitra::distinct()->pluck('nama_perusahaan'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

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
            'status',
            'jenisMitra',
            'programKeahlianId',
            'search',
            'kerjaSamaList',
            'categories',
            'programKeahlians',
            'ptSuggestions'
        ));
    }

    /**
     * Ekspor data kerja sama ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $year = $request->input('year');
        $status = $request->input('status');
        $jenisMitra = $request->input('jenis_mitra');
        $programKeahlianId = $request->input('program_keahlian_id');
        $search = $request->input('search');

        $query = KerjaSama::with(['kategoriMitra', 'programKeahlian']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                  ->orWhere('nomor_mou', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
            });
        }

        if ($jenisMitra) {
            $query->where('jenis_mitra', $jenisMitra);
        }

        if ($programKeahlianId) {
            $query->where('program_keahlian_id', $programKeahlianId);
        }

        $today = Carbon::today();
        $sixMonthsLater = Carbon::today()->addDays(183);

        if ($status) {
            if ($status === 'Berakhir') {
                $query->where('tanggal_berakhir', '<', $today);
            } elseif ($status === 'Akan Berakhir') {
                $query->whereBetween('tanggal_berakhir', [$today, $sixMonthsLater]);
            } elseif ($status === 'Aktif') {
                $query->where('tanggal_berakhir', '>', $sixMonthsLater);
            }
        }

        if ($year) {
            $query->whereYear('tanggal_mulai', $year);
        }

        $kerjaSama = $query->orderBy('tanggal_mulai', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kerja Sama');

        // Title
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'Laporan Data Kerja Sama & MoU');

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

        // Info filter yang diterapkan
        $sheet->mergeCells('A2:L2');
        $filterTexts = [];
        if ($search) $filterTexts[] = 'Cari: "' . $search . '"';
        if ($jenisMitra) $filterTexts[] = 'Kategori: ' . $jenisMitra;
        if ($status) $filterTexts[] = 'Status: ' . $status;
        if ($year) $filterTexts[] = 'Tahun Mulai: ' . $year;
        if ($programKeahlianId) {
            $pk = ProgramKeahlian::find($programKeahlianId);
            if ($pk) $filterTexts[] = 'Jurusan: ' . $pk->nama;
        }

        $infoText = 'Diekspor pada: ' . date('d F Y H:i:s');
        if (!empty($filterTexts)) {
            $infoText .= ' | Filter: ' . implode(', ', $filterTexts);
        }
        $infoText .= ' | Total Data: ' . count($kerjaSama);
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

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(25);  // Nama Mitra
        $sheet->getColumnDimension('C')->setWidth(20);  // Kategori/Jenis
        $sheet->getColumnDimension('D')->setWidth(25);  // Program Keahlian (Jurusan)
        $sheet->getColumnDimension('E')->setWidth(35);  // Alamat
        $sheet->getColumnDimension('F')->setWidth(25);  // Email
        $sheet->getColumnDimension('G')->setWidth(18);  // No. Telepon
        $sheet->getColumnDimension('H')->setWidth(20);  // PIC Mitra
        $sheet->getColumnDimension('I')->setWidth(25);  // Nomor MoU
        $sheet->getColumnDimension('J')->setWidth(15);  // Tanggal Mulai
        $sheet->getColumnDimension('K')->setWidth(15);  // Tanggal Berakhir
        $sheet->getColumnDimension('L')->setWidth(15);  // Status

        // Header
        $headers = [
            'No', 
            'Nama Mitra', 
            'Kategori/Jenis', 
            'Program Keahlian (Jurusan)', 
            'Alamat', 
            'Email', 
            'No. Telepon', 
            'PIC Mitra', 
            'Nomor MoU', 
            'Tanggal Mulai', 
            'Tanggal Berakhir', 
            'Status'
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

        $sheet->getStyle('A4:L4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Data rows
        $row = 5;
        $borderStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ];

        foreach ($kerjaSama as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_mitra);
            $sheet->setCellValue('C' . $row, $item->jenis_mitra);
            $sheet->setCellValue('D' . $row, $item->programKeahlian ? $item->programKeahlian->nama : 'Semua Jurusan');
            $sheet->setCellValue('E' . $row, $item->alamat);
            $sheet->setCellValue('F' . $row, $item->email);
            $sheet->setCellValue('G' . $row, $item->nomor_telepon);
            $sheet->setCellValue('H' . $row, $item->pic);
            $sheet->setCellValue('I' . $row, $item->nomor_mou);
            $sheet->setCellValue('J' . $row, $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-');
            $sheet->setCellValue('K' . $row, $item->tanggal_berakhir ? $item->tanggal_berakhir->format('d/m/Y') : '-');
            $sheet->setCellValue('L' . $row, $item->status);

            $rowStyle = [
                'font' => [
                    'size' => 9,
                    'name' => 'Times New Roman',
                    'color' => ['rgb' => '333333'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false,
                ],
                'border' => $borderStyle,
            ];

            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray($rowStyle);
            $sheet->getRowDimension($row)->setRowHeight(18);

            // Alignments
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $sheet->freezePane('A5');

        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setBottom(0.75);

        $filename = 'laporan-kerja-sama-' . date('Y-m-d-His') . '.xlsx';

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
