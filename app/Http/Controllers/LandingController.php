<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use App\Models\LowonganKerja;
use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use App\Models\Universitas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page publik.
     */
    public function index()
    {
        $totalMitra = KerjaSama::count();
        $totalLowongan = LowonganKerja::where('status', 'Aktif')->count();

        $totalAlumniBekerja = AlumniBekerja::count();
        $totalAlumniKuliah = AlumniKuliah::count();
        $totalAlumniWirausaha = AlumniWirausaha::count();
        $totalAlumni = $totalAlumniBekerja + $totalAlumniKuliah + $totalAlumniWirausaha;

        // Fetch latest 3 active jobs to display on the landing section
        $lowonganTerbaru = LowonganKerja::with('perusahaanMitra')
            ->where('status', 'Aktif')
            ->latest()
            ->take(3)
            ->get();

        return view('pages.landing.index', compact(
            'totalMitra',
            'totalLowongan',
            'totalAlumni',
            'totalAlumniBekerja',
            'totalAlumniKuliah',
            'totalAlumniWirausaha',
            'lowonganTerbaru'
        ));
    }

    /**
     * Tampilkan semua lowongan kerja publik dengan pencarian & filter.
     */
    public function lowonganIndex(Request $request)
    {
        $search = $request->input('search');

        $lowongans = LowonganKerja::with('perusahaanMitra')
            ->where('status', 'Aktif')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('perusahaan_nama', 'like', "%{$search}%")
                      ->orWhere('posisi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('pages.landing.lowongan-index', compact('lowongans', 'search'));
    }

    /**
     * Tampilkan detail lowongan kerja di halaman publik.
     */
    public function lowonganDetail($id)
    {
        $lowongan = LowonganKerja::with('perusahaanMitra')->findOrFail($id);
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.landing.lowongan-detail', compact('lowongan', 'programKeahlians'));
    }

    /**
     * Tampilkan form tracer study di halaman publik.
     */
    public function tracerStudyForm()
    {
        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.landing.tracer-study', compact('programKeahlians'));
    }

    public function submitTracerStudy(Request $request)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'nis' => 'required_if:status_alumni,Kuliah|nullable|string|max:20',
            'tahun_lulus' => 'required|numeric|digits:4|min:1990|max:' . date('Y'),
            'program_keahlian' => 'required|string|max:255',
            'status_alumni' => 'required|in:Bekerja,Kuliah,Wirausaha,Mencari Kerja',
            'detail_status' => 'required_unless:status_alumni,Mencari Kerja|nullable|string|max:255',

            // Khusus Bekerja (tabel alumni_bekerjas)
            'jabatan' => 'required_if:status_alumni,Bekerja|nullable|string|max:255',
            'bidang_industri' => 'required_if:status_alumni,Bekerja|nullable|string|max:255',
            'lokasi_kerja' => 'required_if:status_alumni,Bekerja|nullable|string|max:255',
            'status_pekerjaan' => 'required_if:status_alumni,Bekerja|nullable|in:Tetap,Kontrak,Magang',
            'tanggal_masuk' => 'required_if:status_alumni,Bekerja|nullable|date|before_or_equal:today',
            'gaji' => 'nullable|numeric|min:0',

            // Khusus Kuliah (tabel alumni_kuliah)
            'program_studi' => 'required_if:status_alumni,Kuliah|nullable|string|max:255',
            'email_alumni' => 'nullable|email|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'cara_masuk' => 'required_if:status_alumni,Kuliah|nullable|in:snbp,utbk,ujian_masuk,beasiswa,transfer,lainnya',
            'status_kuliah' => 'required_if:status_alumni,Kuliah|nullable|in:aktif,lulus,cuti,belum_terdata',

            // Khusus Wirausaha (tabel alumni_wirausahas)
            'bidang_usaha' => 'required_if:status_alumni,Wirausaha|nullable|string|max:255',
            'lama_usaha' => 'required_if:status_alumni,Wirausaha|nullable|string|max:255',

            'testimoni' => 'nullable|string',
        ], [
            'nama_alumni.required' => 'Nama lengkap wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'tahun_lulus.digits' => 'Tahun lulus harus 4 digit.',
            'program_keahlian.required' => 'Jurusan wajib dipilih.',
            'status_alumni.required' => 'Status saat ini wajib dipilih.',
            'detail_status.required_unless' => 'Kolom ini wajib diisi sesuai status Anda.',
            'jabatan.required_if' => 'Jabatan wajib diisi.',
            'bidang_industri.required_if' => 'Bidang industri wajib diisi.',
            'lokasi_kerja.required_if' => 'Lokasi kerja wajib diisi.',
            'status_pekerjaan.required_if' => 'Status kepegawaian wajib dipilih.',
            'tanggal_masuk.required_if' => 'Tanggal mulai bekerja wajib diisi.',
            'program_studi.required_if' => 'Program studi wajib diisi.',
            'cara_masuk.required_if' => 'Cara masuk kuliah wajib dipilih.',
            'status_kuliah.required_if' => 'Status perkuliahan wajib dipilih.',
            'bidang_usaha.required_if' => 'Bidang usaha wajib diisi.',
            'lama_usaha.required_if' => 'Lama usaha wajib diisi.',
        ]);

        // NIS hanya unik di tabel alumni_kuliah (kolom unik ada di migrasi tabel ini saja)
        if ($validated['status_alumni'] === 'Kuliah') {
            $request->validate([
                'nis' => Rule::unique('alumni_kuliah', 'nis'),
            ], [
                'nis.unique' => 'NIS ini sudah terdaftar sebagai alumni yang melanjutkan kuliah.',
            ]);
        }

        switch ($validated['status_alumni']) {
            case 'Bekerja':
                AlumniBekerja::create([
                    'nama_alumni' => $validated['nama_alumni'],
                    'perusahaan_nama' => $validated['detail_status'],
                    'jabatan' => $validated['jabatan'],
                    'bidang_industri' => $validated['bidang_industri'],
                    'lokasi_kerja' => $validated['lokasi_kerja'],
                    'status_pekerjaan' => $validated['status_pekerjaan'],
                    'tanggal_masuk' => $validated['tanggal_masuk'],
                    'gaji' => $validated['gaji'] ?? null,
                    'tahun_lulus' => $validated['tahun_lulus'],
                ]);
                break;

            case 'Kuliah':
                $universitas = Universitas::firstOrCreate(
                    ['nama_universitas' => $validated['detail_status']]
                );

                AlumniKuliah::create([
                    'nama_alumni' => $validated['nama_alumni'],
                    'nis' => $validated['nis'],
                    'universitas_id' => $universitas->id,
                    'program_studi' => $validated['program_studi'],
                    'email_alumni' => $validated['email_alumni'] ?? null,
                    'nomor_telepon' => $validated['nomor_telepon'] ?? null,
                    'cara_masuk' => $validated['cara_masuk'],
                    'status_alumni' => $validated['status_kuliah'],
                    'tahun_lulus' => $validated['tahun_lulus'],
                ]);
                break;

            case 'Wirausaha':
                AlumniWirausaha::create([
                    'nama_alumni' => $validated['nama_alumni'],
                    'nama_usaha' => $validated['detail_status'],
                    'bidang_usaha' => $validated['bidang_usaha'],
                    'lama_usaha' => $validated['lama_usaha'],
                    'tahun_lulus' => $validated['tahun_lulus'],
                ]);
                break;

            case 'Mencari Kerja':
                // Belum ada tabel khusus untuk status ini.
                // Untuk sekarang data ini tidak disimpan kemanapun.
                break;
        }

        return back()->with('success', 'Terima kasih! Data tracer study Anda berhasil dikirim.');
    }

    /**
     * Submit lamaran kerja dari form publik.
     */
    public function submitLamaran(Request $request, $id)
    {
        $lowongan = LowonganKerja::findOrFail($id);

        if ($lowongan->status !== 'Aktif') {
            return back()->with('error', 'Maaf, lowongan kerja ini telah ditutup.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'tahun_lulus' => 'required|numeric|digits:4',
            'program_keahlian' => 'required|string|max:255',
            'surat_lamaran' => 'required|string',
            'file_cv' => 'nullable|file|mimes:pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'program_keahlian.required' => 'Jurusan wajib dipilih.',
            'surat_lamaran.required' => 'Surat lamaran wajib diisi.',
            'file_cv.mimes' => 'File CV harus berformat PDF.',
            'file_cv.max' => 'Ukuran file CV maksimal 2MB.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_cv')) {
            $filePath = $request->file('file_cv')->store('cv_pelamar', 'public');
        }

        \App\Models\Pelamar::create([
            'lowongan_kerja_id' => $lowongan->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'program_keahlian' => $validated['program_keahlian'],
            'surat_lamaran' => $validated['surat_lamaran'],
            'file_cv' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim! BKK akan meninjau lamaran Anda.');
    }
}
