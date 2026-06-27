<?php

namespace App\Http\Controllers;

use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    /**
     * Tampilkan daftar respon Tracer Study.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahunLulus = $request->input('tahun_lulus');

        $tracers = TracerStudy::query()
            ->where('status_alumni', 'Bekerja')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_alumni', 'like', "%{$search}%")
                      ->orWhere('detail_status', 'like', "%{$search}%");
                });
            })
            ->when($tahunLulus, function ($query, $tahunLulus) {
                $query->where('tahun_lulus', $tahunLulus);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahunLulusList = TracerStudy::where('status_alumni', 'Bekerja')
            ->distinct()
            ->pluck('tahun_lulus')
            ->sort()
            ->toArray();

        return view('pages.bkk.tracer-study.index', compact('tracers', 'search', 'tahunLulus', 'tahunLulusList'));
    }

    /**
     * Tampilkan form pengisian Tracer Study baru.
     */
    public function create()
    {
        return view('pages.bkk.tracer-study.create');
    }

    /**
     * Simpan respon Tracer Study ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status_alumni' => 'required|in:Bekerja',
            'detail_status' => 'nullable|string|max:255',
            'testimoni' => 'nullable|string',
        ], [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'status_alumni.required' => 'Status alumni wajib dipilih.',
            'status_alumni.in' => 'Status alumni yang dipilih tidak valid.',
        ]);

        TracerStudy::create($validated);

        return redirect()->route('bkk.tracer-study.index')
            ->with('success', 'Tanggapan Tracer Study berhasil disimpan.');
    }

    /**
     * Tampilkan form ubah respon Tracer Study.
     */
    public function edit($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        return view('pages.bkk.tracer-study.edit', compact('tracer'));
    }

    /**
     * Perbarui respon Tracer Study di database.
     */
    public function update(Request $request, $id)
    {
        $tracer = TracerStudy::findOrFail($id);

        $validated = $request->validate([
            'nama_alumni' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'status_alumni' => 'required|in:Bekerja',
            'detail_status' => 'nullable|string|max:255',
            'testimoni' => 'nullable|string',
        ], [
            'nama_alumni.required' => 'Nama alumni wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'status_alumni.required' => 'Status alumni wajib dipilih.',
            'status_alumni.in' => 'Status alumni yang dipilih tidak valid.',
        ]);

        $tracer->update($validated);

        return redirect()->route('bkk.tracer-study.index')
            ->with('success', 'Data Tracer Study berhasil diperbarui.');
    }

    /**
     * Hapus respon Tracer Study dari database.
     */
    public function destroy($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        $tracer->delete();

        return redirect()->route('bkk.tracer-study.index')
            ->with('success', 'Data Tracer Study berhasil dihapus.');
    }

    /**
     * Hapus multiple respon Tracer Study sekaligus (Bulk Delete).
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih.'
            ], 400);
        }

        try {
            TracerStudy::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Tracer Study berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }
}
