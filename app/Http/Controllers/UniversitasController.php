<?php

namespace App\Http\Controllers;

use App\Models\Universitas;
use Illuminate\Http\Request;

class UniversitasController extends Controller
{
    /**
     * Display a listing of universitas
     */
    public function index(Request $request)
    {
        $query = Universitas::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_universitas', 'like', "%{$search}%")
                ->orWhere('kota', 'like', "%{$search}%")
                ->orWhere('provinsi', 'like', "%{$search}%");
            });
        }

        // Filter Jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $universitas = $query->orderBy('nama_universitas')->paginate(15);

        return view('pages.bk.universitas.index', compact('universitas'));
    }

    /**
     * Show the form for creating a new universitas
     */
    public function create()
    {
        return view('pages.bk.universitas.create');
    }

    /**
     * Store a newly created universitas in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_universitas' => 'required|string|max:255|unique:universitas,nama_universitas',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'jenis' => 'required|in:negeri,swasta',
            'akreditasi' => 'nullable|string|max:5',
            'website' => 'nullable|url|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Universitas::create($validated);

        return redirect()
            ->route('universitas.index')
            ->with('success', 'Universitas berhasil ditambahkan');
    }

    /**
     * Display the specified universitas
     */
    public function show(Universitas $universitas)
    {
        // Count alumni from this university
        $alumniCount = $universitas->alumniKuliah()->count();

        return view('pages.bk.universitas.detail', compact('universitas', 'alumniCount'));
    }

    /**
     * Show the form for editing the specified universitas
     */
    public function edit(Universitas $universitas)
    {
        return view('pages.bk.universitas.update', compact('universitas'));
    }

    /**
     * Update the specified universitas in database
     */
    public function update(Request $request, Universitas $universitas)
    {
        $validated = $request->validate([
            'nama_universitas' => 'required|string|max:255|unique:universitas,nama_universitas,' . $universitas->id,
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'jenis' => 'required|in:negeri,swasta',
            'akreditasi' => 'nullable|string|max:5',
            'website' => 'nullable|url|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $universitas->update($validated);

        return redirect()
            ->route('universitas.show', $universitas)
            ->with('success', 'Universitas berhasil diperbarui');
    }

    /**
     * Remove the specified universitas from database
     */
    public function destroy(Universitas $universitas)
    {
        // Check if universitas has alumni
        $alumniCount = $universitas->alumniKuliah()->count();

        if ($alumniCount > 0) {
            return redirect()
                ->route('universitas.index')
                ->with('error', "Tidak bisa menghapus universitas yang memiliki {$alumniCount} alumni");
        }

        $universitas->delete();

        return redirect()
            ->route('universitas.index')
            ->with('success', 'Universitas berhasil dihapus');
    }
}
