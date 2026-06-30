<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WhatsappLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        // Settings are only accessible to Admin & Admin Jurusan
        if (!auth()->user()->isAdmin() && !auth()->user()->isAdminJurusan()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $settings = Setting::getSettings();
        
        $logs = WhatsappLog::with('kerjaSama')
            ->latest()
            ->paginate(10);

        $kategoriMitra = \App\Models\KategoriMitra::orderBy('nama', 'asc')->get();
        $programKeahlian = \App\Models\ProgramKeahlian::orderBy('nama', 'asc')->get();

        return view('pages.setting.index', compact('settings', 'logs', 'kategoriMitra', 'programKeahlian'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isAdminJurusan()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $request->validate([
            'fonnte_token' => 'nullable|string|max:255',
            'whatsapp_active' => 'nullable',
            'tahun_ajaran_aktif' => 'nullable|string|max:255',
        ]);

        $settings = Setting::getSettings();
        
        $settings->update([
            'fonnte_token' => $request->input('fonnte_token'),
            'whatsapp_active' => $request->has('whatsapp_active'),
            'tahun_ajaran_aktif' => $request->input('tahun_ajaran_aktif', date('Y')),
        ]);

        return redirect()->route('setting.index')
            ->with('success', 'Pengaturan WhatsApp Fonnte berhasil diperbarui.');
    }

    /**
     * Store new partner category.
     */
    public function storeKategori(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_mitra,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori ini sudah terdaftar.',
        ]);

        \App\Models\KategoriMitra::create([
            'nama' => $request->input('nama'),
        ]);

        return redirect()->route('setting.index')
            ->with('success', 'Kategori Jenis Mitra baru berhasil ditambahkan.');
    }

    /**
     * Delete partner category.
     */
    public function destroyKategori($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $kategori = \App\Models\KategoriMitra::findOrFail($id);

        // Disassociate this category from all partnerships before deleting
        \App\Models\KerjaSama::where('kategori_mitra_id', $kategori->id)->update([
            'kategori_mitra_id' => null
        ]);

        $kategori->delete();

        return redirect()->route('setting.index')
            ->with('success', 'Kategori Jenis Mitra berhasil dihapus.');
    }

    /**
     * Store new program keahlian.
     */
    public function storeProgramKeahlian(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255|unique:program_keahlians,nama',
            'singkatan' => 'nullable|string|max:50',
        ], [
            'nama.required' => 'Nama program keahlian wajib diisi.',
            'nama.unique' => 'Nama program keahlian ini sudah terdaftar.',
        ]);

        \App\Models\ProgramKeahlian::create([
            'nama' => $request->input('nama'),
            'singkatan' => $request->input('singkatan'),
        ]);

        return redirect()->route('setting.index')
            ->with('success', 'Program Keahlian baru berhasil ditambahkan.');
    }

    /**
     * Delete program keahlian.
     */
    public function destroyProgramKeahlian($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $program = \App\Models\ProgramKeahlian::findOrFail($id);

        // Disassociate this program from all partnerships before deleting
        \App\Models\KerjaSama::where('program_keahlian_id', $program->id)->update([
            'program_keahlian_id' => null
        ]);

        $program->delete();

        return redirect()->route('setting.index')
            ->with('success', 'Program Keahlian berhasil dihapus.');
    }
}
