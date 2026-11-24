<?php

namespace App\Http\Controllers;

use App\Models\KerjaSama;
use App\Models\LowonganKerja;
use App\Models\AlumniBekerja;
use App\Models\AlumniKuliah;
use App\Models\AlumniWirausaha;
use App\Models\ProgramKeahlian;
use App\Models\TracerStudy;
use App\Models\Universitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $ptSuggestions = LowonganKerja::where('status', 'Aktif')
            ->distinct()
            ->pluck('perusahaan_nama')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('pages.landing.lowongan-index', compact('lowongans', 'search', 'ptSuggestions'));
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
     * Tampilkan halaman verifikasi NIS sebelum mengakses form Tracer Study.
     */
    public function showVerifyNisForm()
    {
        return view('pages.landing.tracer-verify');
    }

    /**
     * Proses verifikasi NIS alumni terhadap database.
     */
    public function verifyNis(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:50',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.string'   => 'Format NIS tidak valid.',
            'nis.max'      => 'NIS maksimal 50 karakter.',
        ]);

        $nis = trim($request->input('nis'));
        $alumni = TracerStudy::findRegisteredAlumniByNis($nis);

        if (!$alumni) {
            return back()
                ->withInput()
                ->withErrors([
                    'nis' => 'NIS tidak terdaftar dalam database alumni SMKN 1 Cirebon. Silakan periksa kembali NIS Anda.',
                ]);
        }

        // Condition B: NIS sudah pernah mengisi Tracer Study
        if (TracerStudy::hasSubmittedByNis($alumni['nis'])) {
            session()->forget('verified_tracer_nis');
            session(['tracer_status_nis' => $alumni['nis']]);

            return redirect()->route('landing.tracer-study.status');
        }

        // Condition A: NIS terdaftar dan belum pernah mengisi Tracer Study
        session()->forget('tracer_status_nis');
        session(['verified_tracer_nis' => $alumni['nis']]);

        return redirect()->route('landing.tracer-study');
    }

    /**
     * Tampilkan halaman status notifikasi bagi NIS yang sudah pernah mengisi Tracer Study.
     */
    public function tracerStudyStatus()
    {
        $nis = session('tracer_status_nis') ?? session('verified_tracer_nis');

        if (!$nis) {
            return redirect()->route('landing.tracer-study.verify');
        }

        $submission = TracerStudy::where('nis', $nis)->first();
        $alumni = TracerStudy::findRegisteredAlumniByNis($nis);

        if (!$alumni && $submission) {
            $alumni = [
                'nis'              => $submission->nis,
                'nama_alumni'      => $submission->nama_alumni,
                'tahun_lulus'      => $submission->tahun_lulus,
                'program_keahlian' => $submission->program_keahlian,
            ];
        }

        if (!$alumni || !$submission) {
            return redirect()->route('landing.tracer-study.verify');
        }

        return view('pages.landing.tracer-status', compact('alumni', 'submission'));
    }

    /**
     * Tampilkan form tracer study di halaman publik (hanya untuk NIS yang sudah diverifikasi dan belum mengisi).
     */
    public function tracerStudyForm()
    {
        $verifiedNis = session('verified_tracer_nis');

        if (!$verifiedNis) {
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'Silakan verifikasi NIS Anda terlebih dahulu sebelum mengisi formulir Tracer Study.']);
        }

        $verifiedAlumni = TracerStudy::findRegisteredAlumniByNis($verifiedNis);

        if (!$verifiedAlumni) {
            session()->forget('verified_tracer_nis');
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'NIS tidak valid atau tidak terdaftar. Silakan verifikasi ulang.']);
        }

        if (TracerStudy::hasSubmittedByNis($verifiedAlumni['nis'])) {
            session()->forget('verified_tracer_nis');
            session(['tracer_status_nis' => $verifiedAlumni['nis']]);
            return redirect()->route('landing.tracer-study.status');
        }

        $programKeahlians = ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.landing.tracer-study', compact('programKeahlians', 'verifiedAlumni'));
    }

    public function submitTracerStudy(Request $request)
    {
        $verifiedNis = session('verified_tracer_nis');

        // Jika akses langsung tanpa sesi verifikasi, tolak
        if (!$verifiedNis) {
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'Silakan verifikasi NIS Anda terlebih dahulu sebelum mengirim formulir Tracer Study.']);
        }

        // Pastikan NIS yang dikirim dari form (jika ada) tidak dimanipulasi berbeda dari NIS sesi yang terverifikasi
        if ($request->filled('nis') && strcasecmp(trim($request->input('nis')), trim($verifiedNis)) !== 0) {
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'NIS tidak sesuai dengan sesi verifikasi aktif. Silakan verifikasi ulang.']);
        }

        $alumni = TracerStudy::findRegisteredAlumniByNis($verifiedNis);

        if (!$alumni) {
            session()->forget('verified_tracer_nis');
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'NIS tidak terdaftar dalam database alumni.']);
        }

        // Cek di backend apakah NIS sudah pernah mengisi
        if (TracerStudy::hasSubmittedByNis($alumni['nis'])) {
            session()->forget('verified_tracer_nis');
            session(['tracer_status_nis' => $alumni['nis']]);
            return redirect()->route('landing.tracer-study.status');
        }

        $hasDbMajor = !empty($alumni['program_keahlian']);
        $hasDbTahunLulus = !empty($alumni['tahun_lulus']);

        $validated = $request->validate([
            'kelas'       => 'nullable|string|max:50',
            'no_wa'       => 'nullable|string|max:20',
            'tahun_lulus' => ($hasDbTahunLulus ? 'nullable' : 'required') . '|numeric|digits:4|min:1990|max:' . date('Y'),
            'program_keahlian' => ($hasDbMajor ? 'nullable' : 'required') . '|string|max:255',
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

        try {
            DB::transaction(function () use ($verifiedNis, $validated, &$alumni) {
                $lockedAlumni = TracerStudy::findRegisteredAlumniByNis($verifiedNis, true);
                if (!$lockedAlumni) {
                    throw new \RuntimeException('ALUMNI_NOT_FOUND');
                }

                $alumni = $lockedAlumni;
                $nis = $alumni['nis'];

                // Cek kembali di dalam transaksi terkunci (mencegah race condition / simultaneous requests)
                if (TracerStudy::hasSubmittedByNis($nis, true)) {
                    throw new \RuntimeException('ALREADY_SUBMITTED');
                }

                $namaAlumni = $alumni['nama_alumni'];
                $tahunLulus = $alumni['tahun_lulus'] ?: ($validated['tahun_lulus'] ?? date('Y'));
                $programKeahlian = $alumni['program_keahlian'] ?: ($validated['program_keahlian'] ?? null);
                $kelas = $validated['kelas'] ?? $alumni['kelas'];
                $noWa = $validated['no_wa'] ?? $alumni['no_wa'];

                // Perbarui informasi tahun_lulus, kelas, & jurusan pada StudentNis jika belum terisi
                if (($alumni['source'] ?? '') === 'student_nis' && isset($alumni['model'])) {
                    $studentModel = $alumni['model'];
                    $updates = [];
                    if (empty($studentModel->tahun_lulus) && !empty($tahunLulus)) {
                        $updates['tahun_lulus'] = $tahunLulus;
                    }
                    if (empty($studentModel->kelas) && !empty($kelas)) {
                        $updates['kelas'] = $kelas;
                    }
                    if (empty($studentModel->program_keahlian_id) && !empty($programKeahlian)) {
                        $pkId = \App\Models\ProgramKeahlian::where('nama', $programKeahlian)->value('id');
                        if ($pkId) {
                            $updates['program_keahlian_id'] = $pkId;
                        }
                    }
                    if (!empty($updates)) {
                        $studentModel->update($updates);
                    }
                }

                // 1. Catat submission Tracer Study (kolom nis memiliki constraint UNIQUE di database)
                TracerStudy::create([
                    'nis'              => $nis,
                    'nama_alumni'      => $namaAlumni,
                    'tahun_lulus'      => $tahunLulus,
                    'program_keahlian' => $programKeahlian,
                    'kelas'            => $kelas,
                    'no_wa'            => $noWa,
                    'status_alumni'    => $validated['status_alumni'],
                    'detail_status'    => $validated['status_alumni'] === 'Mencari Kerja' ? '-' : ($validated['detail_status'] ?? '-'),
                    'testimoni'        => $validated['testimoni'] ?? null,
                ]);

                // 2. Simpan/perbarui ke tabel sesuai status alumni
                switch ($validated['status_alumni']) {
                    case 'Bekerja':
                        AlumniBekerja::updateOrCreate(
                            ['nis' => $nis],
                            [
                                'nama_alumni'      => $namaAlumni,
                                'kelas'            => $kelas,
                                'no_wa'            => $noWa,
                                'perusahaan_nama'  => $validated['detail_status'],
                                'jabatan'          => $validated['jabatan'],
                                'bidang_industri'  => $validated['bidang_industri'],
                                'lokasi_kerja'     => $validated['lokasi_kerja'],
                                'status_pekerjaan' => $validated['status_pekerjaan'],
                                'tanggal_masuk'    => $validated['tanggal_masuk'],
                                'gaji'             => $validated['gaji'] ?? null,
                                'tahun_lulus'      => $tahunLulus,
                                'program_keahlian' => $programKeahlian,
                            ]
                        );
                        break;

                    case 'Kuliah':
                        $universitas = Universitas::firstOrCreate(
                            ['nama_universitas' => $validated['detail_status']]
                        );

                        AlumniKuliah::updateOrCreate(
                            ['nis' => $nis],
                            [
                                'nama_alumni'      => $namaAlumni,
                                'kelas'            => $kelas,
                                'no_wa'            => $noWa,
                                'universitas_id'   => $universitas->id,
                                'program_studi'    => $validated['program_studi'],
                                'email_alumni'     => $validated['email_alumni'] ?? null,
                                'nomor_telepon'    => $validated['nomor_telepon'] ?? $noWa,
                                'cara_masuk'       => $validated['cara_masuk'],
                                'status_alumni'    => $validated['status_kuliah'],
                                'tahun_lulus'      => $tahunLulus,
                                'program_keahlian' => $programKeahlian,
                            ]
                        );
                        break;

                    case 'Wirausaha':
                        AlumniWirausaha::updateOrCreate(
                            ['nis' => $nis],
                            [
                                'nama_alumni'      => $namaAlumni,
                                'kelas'            => $kelas,
                                'no_wa'            => $noWa,
                                'nama_usaha'       => $validated['detail_status'],
                                'bidang_usaha'     => $validated['bidang_usaha'],
                                'lama_usaha'       => $validated['lama_usaha'],
                                'tahun_lulus'      => $tahunLulus,
                                'program_keahlian' => $programKeahlian,
                            ]
                        );
                        break;

                    case 'Mencari Kerja':
                        // Data sudah tersimpan di tabel tracer_studies
                        break;
                }
            });
        } catch (\Illuminate\Database\QueryException $e) {
            session()->forget('verified_tracer_nis');
            session(['tracer_status_nis' => $alumni['nis']]);
            return redirect()->route('landing.tracer-study.status');
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'ALREADY_SUBMITTED') {
                session()->forget('verified_tracer_nis');
                session(['tracer_status_nis' => $alumni['nis']]);
                return redirect()->route('landing.tracer-study.status');
            }

            session()->forget('verified_tracer_nis');
            return redirect()->route('landing.tracer-study.verify')
                ->withErrors(['nis' => 'NIS tidak terdaftar dalam database alumni.']);
        }

        session()->forget('verified_tracer_nis');
        session(['tracer_status_nis' => $alumni['nis']]);

        return redirect()->route('landing.tracer-study.status')
            ->with('success', 'Terima kasih! Data tracer study Anda berhasil dikirim.');
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
