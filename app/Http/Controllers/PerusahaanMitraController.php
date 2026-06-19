<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerusahaanMitraController extends Controller
{
    /**
     * Tampilkan daftar perusahaan mitra.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $industri = $request->input('industri');

        $mitras = PerusahaanMitra::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_perusahaan', 'like', "%{$search}%")
                      ->orWhere('pic', 'like', "%{$search}%");
            })
            ->when($industri, function ($query, $industri) {
                $query->where('bidang_industri', $industri);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar bidang industri unik untuk filter
        $industriList = PerusahaanMitra::distinct()->pluck('bidang_industri')->filter()->toArray();

        return view('pages.perusahaan-mitra.index', compact('mitras', 'search', 'industri', 'industriList'));
    }

    /**
     * Tampilkan form tambah perusahaan mitra.
     */
    public function create()
    {
        return view('pages.perusahaan-mitra.create');
    }

    /**
     * Simpan perusahaan mitra baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_industri' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:50',
            'pic' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'bidang_industri.required' => 'Bidang industri wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon PIC wajib diisi.',
            'pic.required' => 'Nama PIC wajib diisi.',
            'website.url' => 'Format alamat website tidak valid (harus diawali http:// atau https://).',
            'status_aktif.required' => 'Status keaktifan wajib dipilih.',
        ]);

        PerusahaanMitra::create($validated);

        return redirect()->route('perusahaan-mitra.index')
            ->with('success', 'Perusahaan mitra berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail perusahaan mitra.
     */
    public function show($id)
    {
        $mitra = PerusahaanMitra::with(['alumniBekerja', 'lowonganKerja'])->findOrFail($id);
        return view('pages.perusahaan-mitra.show', compact('mitra'));
    }

    /**
     * Tampilkan form ubah perusahaan mitra.
     */
    public function edit($id)
    {
        $mitra = PerusahaanMitra::findOrFail($id);
        return view('pages.perusahaan-mitra.edit', compact('mitra'));
    }

    /**
     * Perbarui data perusahaan mitra di database.
     */
    public function update(Request $request, $id)
    {
        $mitra = PerusahaanMitra::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_industri' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:50',
            'pic' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'bidang_industri.required' => 'Bidang industri wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_telepon.required' => 'Nomor telepon PIC wajib diisi.',
            'pic.required' => 'Nama PIC wajib diisi.',
            'website.url' => 'Format alamat website tidak valid (harus diawali http:// atau https://).',
            'status_aktif.required' => 'Status keaktifan wajib dipilih.',
        ]);

        $mitra->update($validated);

        return redirect()->route('perusahaan-mitra.index')
            ->with('success', 'Data perusahaan mitra berhasil diperbarui.');
    }

    /**
     * Hapus perusahaan mitra dari database.
     */
    public function destroy($id)
    {
        $mitra = PerusahaanMitra::findOrFail($id);
        $mitra->delete();

        return redirect()->route('perusahaan-mitra.index')
            ->with('success', 'Perusahaan mitra berhasil dihapus.');
    }
}
