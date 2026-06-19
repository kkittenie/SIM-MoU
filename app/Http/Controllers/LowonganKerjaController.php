<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use App\Models\PerusahaanMitra;
use Illuminate\Http\Request;

class LowonganKerjaController extends Controller
{
    /**
     * Tampilkan daftar lowongan kerja.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $lowongans = LowonganKerja::query()
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('perusahaan_nama', 'like', "%{$search}%")
                      ->orWhere('posisi', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.lowongan-kerja.index', compact('lowongans', 'search', 'status'));
    }

    /**
     * Tampilkan form tambah lowongan kerja.
     */
    public function create()
    {
        $mitras = PerusahaanMitra::where('status_aktif', 'Aktif')->orderBy('nama_perusahaan')->get();
        return view('pages.lowongan-kerja.create', compact('mitras'));
    }

    /**
     * Simpan lowongan kerja baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'perusahaan_mitra_id' => 'nullable|exists:perusahaan_mitras,id',
            'perusahaan_nama' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'posisi' => 'required|string|max:255',
            'persyaratan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gaji' => 'nullable|string|max:255',
            'tanggal_tutup' => 'nullable|date',
            'status' => 'required|in:Aktif,Tutup',
        ];

        $messages = [
            'judul.required' => 'Judul lowongan wajib diisi.',
            'perusahaan_nama.required_without' => 'Nama perusahaan wajib diisi jika tidak memilih dari perusahaan mitra.',
            'posisi.required' => 'Posisi kerja wajib diisi.',
            'persyaratan.required' => 'Persyaratan lowongan wajib diisi.',
            'tanggal_tutup.date' => 'Format tanggal penutupan tidak valid.',
            'status.required' => 'Status lowongan wajib dipilih.',
        ];

        $validated = $request->validate($rules, $messages);

        if (!empty($validated['perusahaan_mitra_id'])) {
            $mitra = PerusahaanMitra::find($validated['perusahaan_mitra_id']);
            $validated['perusahaan_nama'] = $mitra->nama_perusahaan;
        }

        LowonganKerja::create($validated);

        return redirect()->route('lowongan-kerja.index')
            ->with('success', 'Lowongan kerja berhasil dipublikasikan.');
    }

    /**
     * Tampilkan detail lowongan kerja.
     */
    public function show($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        return view('pages.lowongan-kerja.show', compact('lowongan'));
    }

    /**
     * Tampilkan form ubah lowongan kerja.
     */
    public function edit($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        $mitras = PerusahaanMitra::where('status_aktif', 'Aktif')->orderBy('nama_perusahaan')->get();
        return view('pages.lowongan-kerja.edit', compact('lowongan', 'mitras'));
    }

    /**
     * Perbarui data lowongan kerja di database.
     */
    public function update(Request $request, $id)
    {
        $lowongan = LowonganKerja::findOrFail($id);

        $rules = [
            'judul' => 'required|string|max:255',
            'perusahaan_mitra_id' => 'nullable|exists:perusahaan_mitras,id',
            'perusahaan_nama' => 'required_without:perusahaan_mitra_id|nullable|string|max:255',
            'posisi' => 'required|string|max:255',
            'persyaratan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gaji' => 'nullable|string|max:255',
            'tanggal_tutup' => 'nullable|date',
            'status' => 'required|in:Aktif,Tutup',
        ];

        $messages = [
            'judul.required' => 'Judul lowongan wajib diisi.',
            'perusahaan_nama.required_without' => 'Nama perusahaan wajib diisi jika tidak memilih dari perusahaan mitra.',
            'posisi.required' => 'Posisi kerja wajib diisi.',
            'persyaratan.required' => 'Persyaratan lowongan wajib diisi.',
            'tanggal_tutup.date' => 'Format tanggal penutupan tidak valid.',
            'status.required' => 'Status lowongan wajib dipilih.',
        ];

        $validated = $request->validate($rules, $messages);

        if (!empty($validated['perusahaan_mitra_id'])) {
            $mitra = PerusahaanMitra::find($validated['perusahaan_mitra_id']);
            $validated['perusahaan_nama'] = $mitra->nama_perusahaan;
        }

        $lowongan->update($validated);

        return redirect()->route('lowongan-kerja.index')
            ->with('success', 'Data lowongan kerja berhasil diperbarui.');
    }

    /**
     * Hapus lowongan kerja dari database.
     */
    public function destroy($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        $lowongan->delete();

        return redirect()->route('lowongan-kerja.index')
            ->with('success', 'Lowongan kerja berhasil dihapus.');
    }
}
