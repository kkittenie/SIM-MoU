<?php

namespace App\Http\Controllers;

use App\Models\AlumniWirausaha;
use Illuminate\Http\Request;

class AlumniWirausahaController extends Controller
{
    /**
     * Tampilkan daftar alumni wirausaha.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahunLulus = $request->has('tahun_lulus') ? $request->input('tahun_lulus') : \App\Models\Setting::getActiveTahunAjaran();

        $alumni = AlumniWirausaha::query()
            ->tahunAjaranAktif()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_alumni', 'like', "%{$search}%")
                      ->orWhere('nama_usaha', 'like', "%{$search}%")
                      ->orWhere('bidang_usaha', 'like', "%{$search}%");
                });
            })
            ->when($tahunLulus, function ($query, $tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tahunLulusList = AlumniWirausaha::distinct()->pluck('tahun_lulus')->sort()->toArray();

        return view('pages.bk.alumni-wirausaha.index', compact('alumni', 'search', 'tahunLulus', 'tahunLulusList'));
    }

    /**
     * Tampilkan form tambah alumni wirausaha.
     */
    public function create()
    {
        return view('pages.bk.alumni-wirausaha.create');
    }

    /**
     * Simpan data alumni wirausaha baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'nama_usaha' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'lama_usaha' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ], [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'nama_usaha.required' => 'Nama usaha wajib diisi.',
            'bidang_usaha.required' => 'Bidang usaha wajib diisi.',
            'lama_usaha.required' => 'Lama usaha wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
        ]);

        AlumniWirausaha::create($validated);

        return redirect()->route('bk.alumni-wirausaha.index')
            ->with('success', 'Data Alumni Wirausaha berhasil ditambahkan');
    }

    /**
     * Tampilkan detail alumni wirausaha.
     */
    public function show(AlumniWirausaha $alumniWirausaha)
    {
        return view('pages.bk.alumni-wirausaha.show', ['alumni' => $alumniWirausaha]);
    }

    /**
     * Tampilkan form ubah alumni wirausaha.
     */
    public function edit(AlumniWirausaha $alumniWirausaha)
    {
        return view('pages.bk.alumni-wirausaha.update', compact('alumniWirausaha'));
    }

    /**
     * Perbarui data alumni wirausaha.
     */
    public function update(Request $request, AlumniWirausaha $alumniWirausaha)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'nama_usaha' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:255',
            'lama_usaha' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
        ], [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'nama_usaha.required' => 'Nama usaha wajib diisi.',
            'bidang_usaha.required' => 'Bidang usaha wajib diisi.',
            'lama_usaha.required' => 'Lama usaha wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
        ]);

        $alumniWirausaha->update($validated);

        return redirect()->route('bk.alumni-wirausaha.index')
            ->with('success', 'Data Alumni Wirausaha berhasil diperbarui');
    }

    /**
     * Hapus data alumni wirausaha.
     */
    public function destroy(AlumniWirausaha $alumniWirausaha)
    {
        $alumniWirausaha->delete();

        return redirect()->route('bk.alumni-wirausaha.index')
            ->with('success', 'Data Alumni Wirausaha berhasil dihapus');
    }

    /**
     * Bulk delete wirausaha.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal 1 data untuk dihapus'
            ], 400);
        }

        try {
            $deletedCount = AlumniWirausaha::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' data Alumni Wirausaha berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }
}
