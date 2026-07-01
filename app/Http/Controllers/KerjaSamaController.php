<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjaSamaController extends Controller
{
    /**
     * Tampilkan daftar kerja sama dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenisMitra = $request->input('jenis_mitra');
        $status = $request->input('status');
        $year = $request->input('year');
        $programKeahlianId = $request->input('program_keahlian_id');

        $today = Carbon::today();
        $sixMonthsLater = Carbon::today()->addDays(183);

        $query = KerjaSama::query();

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

        if ($year) {
            $query->whereYear('tanggal_mulai', $year);
        }

        if ($programKeahlianId) {
            $query->where('program_keahlian_id', $programKeahlianId);
        }

        if ($status) {
            if ($status === 'Berakhir') {
                $query->where('tanggal_berakhir', '<', $today);
            } elseif ($status === 'Akan Berakhir') {
                $query->whereBetween('tanggal_berakhir', [$today, $sixMonthsLater]);
            } elseif ($status === 'Aktif') {
                $query->where('tanggal_berakhir', '>', $sixMonthsLater);
            }
        }

        $kerjaSama = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Get unique years for the year filter dropdown
        $yearExpr = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite' 
            ? "strftime('%Y', tanggal_mulai) as year" 
            : "YEAR(tanggal_mulai) as year";
            
        $years = KerjaSama::selectRaw($yearExpr)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->toArray();

        $categories = \App\Models\KategoriMitra::orderBy('nama', 'asc')->get();
        $programKeahlians = \App\Models\ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.kerja-sama.index', compact('kerjaSama', 'search', 'jenisMitra', 'status', 'years', 'year', 'categories', 'programKeahlians', 'programKeahlianId'));
    }

    /**
     * Tampilkan form tambah kerja sama.
     */
    public function create()
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah kerja sama.');
        }

        $categories = \App\Models\KategoriMitra::orderBy('nama', 'asc')->get();
        $programKeahlians = \App\Models\ProgramKeahlian::orderBy('nama', 'asc')->get();
        return view('pages.kerja-sama.create', compact('categories', 'programKeahlians'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah kerja sama.');
        }

        $validated = $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'kategori_mitra_id' => 'required|exists:kategori_mitra,id',
            'program_keahlian_id' => 'nullable|exists:program_keahlians,id',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:50',
            'pic' => 'required|string|max:255',
            'nomor_mou' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string',
            'dokumen_pdf' => 'nullable|file|mimes:pdf|max:10240', // Maks 10MB
        ], [
            'nama_mitra.required' => 'Nama mitra wajib diisi.',
            'kategori_mitra_id.required' => 'Jenis mitra wajib dipilih.',
            'kategori_mitra_id.exists' => 'Jenis mitra tidak valid.',
            'program_keahlian_id.exists' => 'Program Keahlian tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'pic.required' => 'PIC wajib diisi.',
            'nomor_mou.required' => 'Nomor MoU wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi.',
            'tanggal_berakhir.date' => 'Tanggal berakhir harus berupa tanggal yang valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
            'dokumen_pdf.mimes' => 'Dokumen pendukung harus berupa file PDF.',
            'dokumen_pdf.max' => 'Ukuran dokumen pendukung maksimal 10MB.',
        ]);

        if ($request->hasFile('dokumen_pdf')) {
            $path = $request->file('dokumen_pdf')->store('dokumen_mou', 'public');
            $validated['dokumen_pdf'] = $path;
        }

        // Set fallback jenis_mitra string value
        $category = \App\Models\KategoriMitra::find($validated['kategori_mitra_id']);
        $validated['jenis_mitra'] = $category ? $category->nama : 'Lainnya';

        KerjaSama::create($validated);

        return redirect()->route('kerja-sama.index')
            ->with('success', 'Kerja sama berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kerja sama.
     */
    public function show($id)
    {
        $kerjaSama = KerjaSama::findOrFail($id);
        return view('pages.kerja-sama.show', compact('kerjaSama'));
    }

    /**
     * Tampilkan form ubah kerja sama.
     */
    public function edit($id)
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerja sama.');
        }

        $kerjaSama = KerjaSama::findOrFail($id);
        $categories = \App\Models\KategoriMitra::orderBy('nama', 'asc')->get();
        $programKeahlians = \App\Models\ProgramKeahlian::orderBy('nama', 'asc')->get();
        return view('pages.kerja-sama.edit', compact('kerjaSama', 'categories', 'programKeahlians'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerja sama.');
        }

        $kerjaSama = KerjaSama::findOrFail($id);

        $validated = $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'kategori_mitra_id' => 'required|exists:kategori_mitra,id',
            'program_keahlian_id' => 'nullable|exists:program_keahlians,id',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:50',
            'pic' => 'required|string|max:255',
            'nomor_mou' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string',
            'dokumen_pdf' => 'nullable|file|mimes:pdf|max:10240', // Maks 10MB
        ], [
            'nama_mitra.required' => 'Nama mitra wajib diisi.',
            'kategori_mitra_id.required' => 'Jenis mitra wajib dipilih.',
            'kategori_mitra_id.exists' => 'Jenis mitra tidak valid.',
            'program_keahlian_id.exists' => 'Program Keahlian tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'pic.required' => 'PIC wajib diisi.',
            'nomor_mou.required' => 'Nomor MoU wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi.',
            'tanggal_berakhir.date' => 'Tanggal berakhir harus berupa tanggal yang valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
            'dokumen_pdf.mimes' => 'Dokumen pendukung harus berupa file PDF.',
            'dokumen_pdf.max' => 'Ukuran dokumen pendukung maksimal 10MB.',
        ]);

        if ($request->hasFile('dokumen_pdf')) {
            // Hapus file lama jika ada
            if ($kerjaSama->dokumen_pdf) {
                Storage::disk('public')->delete($kerjaSama->dokumen_pdf);
            }
            $path = $request->file('dokumen_pdf')->store('dokumen_mou', 'public');
            $validated['dokumen_pdf'] = $path;
        }

        // Set fallback jenis_mitra string value
        $category = \App\Models\KategoriMitra::find($validated['kategori_mitra_id']);
        $validated['jenis_mitra'] = $category ? $category->nama : 'Lainnya';

        $kerjaSama->update($validated);

        return redirect()->route('kerja-sama.index')
            ->with('success', 'Kerja sama berhasil diperbarui.');
    }

    /**
     * Hapus kerja sama dari database.
     */
    public function destroy($id)
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus kerja sama.');
        }

        $kerjaSama = KerjaSama::findOrFail($id);

        // Hapus file dokumen pendukung jika ada
        if ($kerjaSama->dokumen_pdf) {
            Storage::disk('public')->delete($kerjaSama->dokumen_pdf);
        }

        $kerjaSama->delete();

        return redirect()->route('kerja-sama.index')
            ->with('success', 'Kerja sama berhasil dihapus.');
    }

    /**
     * Unduh dokumen PDF.
     */
    public function download($id)
    {
        $kerjaSama = KerjaSama::findOrFail($id);

        if (!$kerjaSama->dokumen_pdf || !Storage::disk('public')->exists($kerjaSama->dokumen_pdf)) {
            return back()->with('error', 'Dokumen PDF tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $kerjaSama->dokumen_pdf,
            'MoU_' . str_replace(' ', '_', $kerjaSama->nama_mitra) . '.pdf'
        );
    }

    /**
     * Kirim notifikasi WhatsApp pengingat MoU secara manual ke PIC Mitra.
     */
    public function kirimWhatsapp($id)
    {
        if (!auth()->user()->isBKK() && !auth()->user()->isAdminJurusan() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk mengirim notifikasi WhatsApp.');
        }

        $kerjaSama = KerjaSama::findOrFail($id);

        if ($kerjaSama->status === 'Aktif') {
            return back()->with('error', 'Gagal mengirim. Tidak perlu mengirim pengingat WhatsApp untuk MoU yang masih Aktif.');
        }
        
        $settings = \App\Models\Setting::getSettings();
        if (!$settings->whatsapp_active || !$settings->fonnte_token) {
            return back()->with('error', 'Gagal mengirim. Pengaturan WhatsApp belum aktif atau belum lengkap (token API belum diisi). Harap periksa menu Pengaturan.');
        }

        if (!$kerjaSama->nomor_telepon) {
            return back()->with('error', 'Gagal mengirim. Nomor telepon PIC Mitra tidak diisi.');
        }

        // Tentukan tipe pesan berdasarkan sisa hari
        $today = Carbon::today();
        $endDate = Carbon::parse($kerjaSama->tanggal_berakhir);
        $diffDays = $today->diffInDays($endDate, false);

        if ($diffDays <= 0) {
            $type = 'berakhir';
        } elseif ($diffDays < 30) {
            $type = "{$diffDays} hari";
        } else {
            $months = max(1, (int)round($diffDays / 30.4));
            $type = "{$months} bulan";
        }

        $whatsappService = new \App\Services\WhatsappService();
        $success = $whatsappService->sendNotification($kerjaSama, $type);

        if ($success) {
            return back()->with('success', 'Pesan pengingat WhatsApp berhasil dikirim ke nomor PIC Mitra (' . ($kerjaSama->pic ?: 'Bapak/Ibu') . ').');
        } else {
            return back()->with('error', 'Gagal mengirim pesan WhatsApp via Fonnte. Silakan periksa log pengiriman di menu Pengaturan.');
        }
    }
}
