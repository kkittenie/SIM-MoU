<?php

namespace App\Http\Controllers;

use App\Models\TracerKuliah;
use App\Models\AlumniKuliah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TracerKuliahController extends Controller
{
    /**
     * Display a listing of tracer kuliah.
     */
    public function index(Request $request)
    {
        $query = TracerKuliah::query()->with('alumniKuliah');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('alumniKuliah', function ($q) use ($search) {
                $q->where('nama_alumni', 'like', "%{$search}%");
            })->orWhere('kampus_tujuan', 'like', "%{$search}%");
        }

        // Filter by status kuliah
        if ($request->has('status') && $request->status) {
            $query->where('status_kuliah', $request->status);
        }

        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $tracers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pages.bk.tracer-kuliah.index', compact('tracers', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alumniList = AlumniKuliah::orderBy('nama_alumni')->get();
        return view('pages.bk.tracer-kuliah.create', compact('alumniList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumni_kuliah_id' => 'required|exists:alumni_kuliah,id',
            'kampus_tujuan' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'status_kuliah' => 'required|in:aktif,lulus,cuti,putus',
            'detail_status' => 'nullable|string',
            'testimoni' => 'nullable|string',
            'tanggal_update' => 'nullable|date',
        ]);

        TracerKuliah::create($validated);

        return redirect()->route('bk.tracer-kuliah.index')
            ->with('success', 'Data Tracer Study Kuliah berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TracerKuliah $tracerKuliah)
    {
        $alumniList = AlumniKuliah::orderBy('nama_alumni')->get();
        return view('pages.bk.tracer-kuliah.update', [
            'tracer' => $tracerKuliah,
            'alumniList' => $alumniList
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TracerKuliah $tracerKuliah)
    {
        $validated = $request->validate([
            'alumni_kuliah_id' => 'required|exists:alumni_kuliah,id',
            'kampus_tujuan' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'status_kuliah' => 'required|in:aktif,lulus,cuti,putus',
            'detail_status' => 'nullable|string',
            'testimoni' => 'nullable|string',
            'tanggal_update' => 'nullable|date',
        ]);

        $tracerKuliah->update($validated);

        return redirect()->route('bk.tracer-kuliah.index')
            ->with('success', 'Data Tracer Study Kuliah berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TracerKuliah $tracerKuliah)
    {
        $tracerKuliah->delete();

        return redirect()->route('bk.tracer-kuliah.index')
            ->with('success', 'Data Tracer Study Kuliah berhasil dihapus');
    }

    /**
     * Bulk delete tracer kuliah dengan error handling.
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
            $deletedCount = TracerKuliah::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' data Tracer Study Kuliah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Silakan coba lagi.'
            ], 500);
        }
    }
}
