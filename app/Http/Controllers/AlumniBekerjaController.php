<?php

namespace App\Http\Controllers;

use App\Models\AlumniBekerja;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class AlumniBekerjaController extends Controller
{
    /**
     * Tampilkan daftar alumni bekerja.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahunLulus = $request->has('tahun_lulus') ? $request->input('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();
        $statusPekerjaan = $request->input('status_pekerjaan');
        $lokasiKerja = $request->input('lokasi_kerja');

        $alumni = AlumniBekerja::query()
            ->tahunAjaranAktif()
            ->when($search, function ($query, $search) {
                $query->where('nama_alumni', 'like', "%{$search}%")
                      ->orWhere('perusahaan_nama', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%");
            })
            ->when($tahunLulus, function ($query, $tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            })
            ->when($statusPekerjaan, function ($query, $statusPekerjaan) {
                $query->where('status_pekerjaan', $statusPekerjaan);
            })
            ->when($lokasiKerja, function ($query, $lokasiKerja) {
                $query->where('lokasi_kerja', $lokasiKerja);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahunLulusList = AlumniBekerja::distinct()->pluck('tahun_lulus')->sort()->toArray();

        return view('pages.bkk.alumni-bekerja.index', compact('alumni', 'search', 'tahunLulus', 'statusPekerjaan', 'lokasiKerja', 'tahunLulusList'));
    }

    /**
     * Tampilkan form tambah data alumni bekerja.
     */
    public function create()
    {
        $mitras = PerusahaanMitra::where('status_aktif', 'Aktif')->orderBy('nama_perusahaan')->get();
        return view('pages.bkk.alumni-bekerja.create', compact('mitras'));
    }

    /**
     * Simpan data alumni bekerja baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_alumni' => 'required|string|max:255',
            'perusahaan_mitra_id' => 'nullable|exists:perusahaan_mitras,id',
            'perusahaan_nama' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'bidang_industri' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'gaji' => 'nullable|numeric|min:0',
            'status_pekerjaan' => 'required|string|in:Tetap,Kontrak,Magang,Freelance',
            'lokasi_kerja' => 'required|string|in:Dalam Negeri,Luar Negeri',
        ];

        $messages = [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'perusahaan_nama.required_without' => 'Nama perusahaan wajib diisi jika tidak memilih dari perusahaan mitra.',
            'jabatan.required' => 'Jabatan/posisi kerja wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk kerja wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal masuk tidak valid.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'bidang_industri.required_without' => 'Bidang industri wajib diisi jika tidak memilih dari perusahaan mitra.',
            'gaji.numeric' => 'Nominal gaji harus berupa angka.',
            'status_pekerjaan.required' => 'Status pekerjaan (e.g. Tetap/Kontrak/Magang/Freelance) wajib diisi.',
            'status_pekerjaan.in' => 'Status pekerjaan tidak valid.',
            'lokasi_kerja.required' => 'Lokasi kerja wajib diisi.',
            'lokasi_kerja.in' => 'Lokasi kerja tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);

        // Jika memilih perusahaan mitra, isi otomatis nama perusahaan & bidang industrinya
        if (!empty($validated['perusahaan_mitra_id'])) {
            $mitra = PerusahaanMitra::find($validated['perusahaan_mitra_id']);
            $validated['perusahaan_nama'] = $mitra->nama_perusahaan;
            $validated['bidang_industri'] = $mitra->bidang_industri;
        }

        AlumniBekerja::create($validated);

        return redirect()->route('bkk.alumni-bekerja.index')
            ->with('success', 'Data alumni bekerja berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail data alumni bekerja.
     */
    public function show($id)
    {
        $alumni = AlumniBekerja::findOrFail($id);
        return view('pages.bkk.alumni-bekerja.show', compact('alumni'));
    }

    /**
     * Tampilkan form ubah data alumni bekerja.
     */
    public function edit($id)
    {
        $alumni = AlumniBekerja::findOrFail($id);
        $mitras = PerusahaanMitra::where('status_aktif', 'Aktif')->orderBy('nama_perusahaan')->get();
        return view('pages.bkk.alumni-bekerja.edit', compact('alumni', 'mitras'));
    }

    /**
     * Perbarui data alumni bekerja di database.
     */
    public function update(Request $request, $id)
    {
        $alumni = AlumniBekerja::findOrFail($id);

        $rules = [
            'nama_alumni' => 'required|string|max:255',
            'perusahaan_mitra_id' => 'nullable|exists:perusahaan_mitras,id',
            'perusahaan_nama' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'bidang_industri' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'gaji' => 'nullable|numeric|min:0',
            'status_pekerjaan' => 'required|string|in:Tetap,Kontrak,Magang,Freelance',
            'lokasi_kerja' => 'required|string|in:Dalam Negeri,Luar Negeri',
        ];

        $messages = [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'perusahaan_nama.required_without' => 'Nama perusahaan wajib diisi jika tidak memilih dari perusahaan mitra.',
            'jabatan.required' => 'Jabatan/posisi kerja wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk kerja wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal masuk tidak valid.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'bidang_industri.required_without' => 'Bidang industri wajib diisi jika tidak memilih dari perusahaan mitra.',
            'gaji.numeric' => 'Nominal gaji harus berupa angka.',
            'status_pekerjaan.required' => 'Status pekerjaan (e.g. Tetap/Kontrak/Magang/Freelance) wajib diisi.',
            'status_pekerjaan.in' => 'Status pekerjaan tidak valid.',
            'lokasi_kerja.required' => 'Lokasi kerja wajib diisi.',
            'lokasi_kerja.in' => 'Lokasi kerja tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);

        if (!empty($validated['perusahaan_mitra_id'])) {
            $mitra = PerusahaanMitra::find($validated['perusahaan_mitra_id']);
            $validated['perusahaan_nama'] = $mitra->nama_perusahaan;
            $validated['bidang_industri'] = $mitra->bidang_industri;
        }

        $alumni->update($validated);

        return redirect()->route('bkk.alumni-bekerja.index')
            ->with('success', 'Data alumni bekerja berhasil diperbarui.');
    }

    /**
     * Hapus data alumni bekerja dari database.
     */
    public function destroy($id)
    {
        $alumni = AlumniBekerja::findOrFail($id);
        $alumni->delete();

        return redirect()->route('bkk.alumni-bekerja.index')
            ->with('success', 'Data alumni bekerja berhasil dihapus.');
    }
}
