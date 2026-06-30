<?php

namespace App\Http\Controllers;

use App\Models\AlumniKuliah;
use App\Models\Universitas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class AlumniKuliahController extends Controller
{
    /**
     * INDEX - Tampilkan daftar alumni
     */
    public function index(Request $request)
    {
        $query = AlumniKuliah::with('universitas')->tahunAjaranAktif();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_alumni', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('program_studi', 'like', "%{$search}%")
                ->orWhereHas('universitas', function ($q) use ($search) {
                    $q->where('nama_universitas', 'like', "%{$search}%");
                });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status_alumni', $request->status);
        }

        // Filter by tahun lulus
        if ($request->has('tahun_lulus') && $request->tahun_lulus) {
            $query->where('tahun_lulus', $request->tahun_lulus);
        }

        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $tahunLulus = $request->has('tahun_lulus') ? $request->get('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();

        $alumni = $query->orderBy('created_at', 'desc')->paginate(15);
        $tahunLulusList = AlumniKuliah::distinct()->pluck('tahun_lulus')->sort()->toArray();
        $universitas = Universitas::where('status', 'aktif')->orderBy('nama_universitas')->get();

        return view('pages.bk.alumni-kuliah.index', compact('alumni', 'search', 'status', 'tahunLulus', 'tahunLulusList', 'universitas'));
    }

    /**
     * CREATE - Form tambah alumni
     */
    public function create()
    {
        $universitas = Universitas::where('status', 'aktif')->orderBy('nama_universitas')->get();
        return view('pages.bk.alumni-kuliah.create', compact('universitas'));
    }

    /**
     * STORE - Simpan data alumni baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:150',
            'nis' => 'required|string|unique:alumni_kuliah,nis|max:20',
            'tahun_lulus' => 'required|integer|min:2000|max:' . date('Y'),
            'universitas_id' => 'required|exists:universitas,id',
            'program_studi' => 'required|string|max:150',
            'email_alumni' => 'nullable|email|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'status_alumni' => 'required|in:aktif,lulus,cuti,belum_terdata',
        ]);

        AlumniKuliah::create($validated);

        return redirect()->route('bk.alumni-kuliah.index')
            ->with('success', 'Data Alumni Kuliah berhasil ditambahkan');
    }

    /**
     * SHOW - Detail alumni
     */
    public function show(AlumniKuliah $alumniKuliah)
    {
        return view('pages.bk.alumni-kuliah.show', ['alumni' => $alumniKuliah]);
    }

    /**
     * EDIT - Form edit alumni
     */
    public function edit(AlumniKuliah $alumniKuliah)
    {
        $universitas = Universitas::where('status', 'aktif')->orderBy('nama_universitas')->get();
        return view('pages.bk.alumni-kuliah.update', compact('alumniKuliah', 'universitas'));
    }

    /**
     * UPDATE - Update data alumni
     */
    public function update(Request $request, AlumniKuliah $alumniKuliah)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:150',
            'nis' => 'required|string|max:20|unique:alumni_kuliah,nis,' . $alumniKuliah->id,
            'tahun_lulus' => 'required|integer|min:2000|max:' . date('Y'),
            'universitas_id' => 'required|exists:universitas,id',
            'program_studi' => 'required|string|max:150',
            'email_alumni' => 'nullable|email|max:100',
            'nomor_telepon' => 'nullable|string|max:20',
            'status_alumni' => 'required|in:aktif,lulus,cuti,belum_terdata',
        ]);

        $alumniKuliah->update($validated);

        return redirect()->route('bk.alumni-kuliah.index')
            ->with('success', 'Data Alumni Kuliah berhasil diperbarui');
    }

    /**
     * DESTROY - Hapus single alumni
     */
    public function destroy(AlumniKuliah $alumniKuliah)
    {
        $alumniKuliah->delete();

        return redirect()->route('bk.alumni-kuliah.index')
            ->with('success', 'Data Alumni Kuliah berhasil dihapus');
    }

    /**
     * BULK DELETE - Hapus multiple alumni
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal 1 alumni untuk dihapus'
            ], 400);
        }

        try {
            $deletedCount = AlumniKuliah::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' data Alumni Kuliah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * DOWNLOAD TEMPLATE - Download template Excel untuk import
     * SIMPLE & CLEAN - Sama persis seperti export Excel!
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Alumni Kuliah');

        // Set column widths - SAMA DENGAN EXPORT
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(15);

        // ============ TITLE ============
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Data Alumni Kuliah SMKN 1 CIREBON');

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

        // ============ INFO ROW ============
        $sheet->mergeCells('A2:I2');
        $infoText = 'Template Pengisian Data | Isi data mulai dari Baris 5 ke bawah';
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

        // ============ HEADER ROW (Baris 4) ============
        $headers = ['No', 'Nama Alumni', 'NIS', 'Tahun Lulus', 'Universitas', 'Program Studi', 'Email', 'Telepon', 'Status'];
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

        $sheet->getStyle('A4:I4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // ============ EMPTY ROWS UNTUK DIISI (Baris 5-25) ============
        $borderStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ];

        $dataStyle = [
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

        for ($row = 5; $row <= 25; $row++) {
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($dataStyle);
            $sheet->getRowDimension($row)->setRowHeight(18);
        }

        // Freeze pane
        $sheet->freezePane('A5');

        // Page setup
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setBottom(0.75);

        // Generate file
        $filename = 'template-alumni-kuliah-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * IMPORT - Import data alumni dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->path());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header (row 1-4) dan ambil data dari row 5
            $dataRows = array_slice($rows, 4);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($dataRows as $index => $row) {
                // Skip empty rows
                if (empty($row[1]) || empty($row[2])) {
                    continue;
                }

                $rowNum = $index + 5;

                try {
                    // Get universitas by name
                    $universitas = Universitas::where('nama_universitas', trim($row[4]))->first();
                    if (!$universitas) {
                        $errors[] = "Baris {$rowNum}: Universitas '{$row[4]}' tidak ditemukan";
                        $errorCount++;
                        continue;
                    }

                    // Validate and convert status
                    $statusInput = strtolower(trim($row[8]));
                    $statusMap = [
                        'aktif' => 'aktif',
                        'lulus' => 'lulus',
                        'cuti' => 'cuti',
                        'belum_terdata' => 'belum_terdata',
                        'belum terdata' => 'belum_terdata',
                    ];

                    if (!isset($statusMap[$statusInput])) {
                        $errors[] = "Baris {$rowNum}: Status '{$row[8]}' tidak valid. Gunakan: Aktif, Lulus, Cuti, atau Belum Terdata";
                        $errorCount++;
                        continue;
                    }

                    $status = $statusMap[$statusInput];

                    // Check if NIS already exists
                    if (AlumniKuliah::where('nis', trim($row[2]))->exists()) {
                        $errors[] = "Baris {$rowNum}: NIS '{$row[2]}' sudah terdaftar";
                        $errorCount++;
                        continue;
                    }

                    // Create alumni
                    AlumniKuliah::create([
                        'nama_alumni' => trim($row[1]),
                        'nis' => trim($row[2]),
                        'tahun_lulus' => (int)$row[3],
                        'universitas_id' => $universitas->id,
                        'program_studi' => trim($row[5]),
                        'email_alumni' => !empty($row[6]) ? trim($row[6]) : null,
                        'nomor_telepon' => !empty($row[7]) ? trim($row[7]) : null,
                        'status_alumni' => $status,
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                    $errorCount++;
                }
            }

            // Prepare response message
            $message = "{$successCount} data berhasil ditambahkan";
            if ($errorCount > 0) {
                $message .= " | {$errorCount} data gagal";
            }

            if ($successCount == 0 && $errorCount == 0) {
                return redirect()->route('bk.alumni-kuliah.index')
                    ->with('warning', 'File tidak berisi data yang valid');
            }

            $redirectTo = redirect()->route('bk.alumni-kuliah.index')
                ->with('success', $message);

            if (!empty($errors)) {
                session()->flash('import_errors', $errors);
            }

            return $redirectTo;
        } catch (\Exception $e) {
            return redirect()->route('bk.alumni-kuliah.index')
                ->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    /**
     * EXPORT EXCEL - Export data alumni ke Excel
     */
    public function exportExcel(Request $request)
    {
        $query = AlumniKuliah::with('universitas')->tahunAjaranAktif();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_alumni', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status) {
            $query->where('status_alumni', $request->status);
        }

        $alumni = $query->orderBy('tahun_lulus', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Alumni Kuliah');

        // Title
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Daftar Alumni Kuliah SMKN 1 Cirebon');

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

        // Info
        $sheet->mergeCells('A2:I2');
        $infoText = 'Diekspor pada: ' . date('d F Y H:i:s') . ' | Total Data: ' . count($alumni);
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

        // Set column width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(15);

        // Header
        $headers = ['No', 'Nama Alumni', 'NIS', 'Tahun Lulus', 'Universitas', 'Program Studi', 'Email', 'Telepon', 'Status'];
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

        $sheet->getStyle('A4:I4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Data rows
        $row = 5;
        $borderStyle = [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'CCCCCC'],
            ],
        ];

        foreach ($alumni as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nama_alumni);
            $sheet->setCellValue('C' . $row, $item->nis);
            $sheet->setCellValue('D' . $row, $item->tahun_lulus);
            $sheet->setCellValue('E' . $row, $item->universitas->nama_universitas ?? '-');
            $sheet->setCellValue('F' . $row, $item->program_studi);
            $sheet->setCellValue('G' . $row, $item->email_alumni ?? '-');
            $sheet->setCellValue('H' . $row, $item->nomor_telepon ?? '-');
            $sheet->setCellValue('I' . $row, $this->getStatusLabel($item->status_alumni));

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

            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($rowStyle);
            $sheet->getRowDimension($row)->setRowHeight(18);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        $sheet->freezePane('A5');

        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setBottom(0.75);

        $filename = 'alumni-kuliah-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Helper - Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'aktif' => 'Aktif',
            'lulus' => 'Lulus',
            'cuti' => 'Cuti',
            'belum_terdata' => 'Belum Terdata',
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}
