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
        // Settings are only accessible to Admin & BKK
        if (!auth()->user()->isAdmin() && !auth()->user()->isBKK()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $settings = Setting::getSettings();
        
        $logs = WhatsappLog::with('kerjaSama')
            ->latest()
            ->paginate(10);

        $kategoriMitra = \App\Models\KategoriMitra::orderBy('nama', 'asc')->get();

        return view('pages.setting.index', compact('settings', 'logs', 'kategoriMitra'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isBKK()) {
            abort(403, 'Anda tidak memiliki akses ke pengaturan.');
        }

        $request->validate([
            'fonnte_token' => 'nullable|string|max:255',
            'whatsapp_active' => 'nullable',
        ]);

        $settings = Setting::getSettings();
        
        $settings->update([
            'fonnte_token' => $request->input('fonnte_token'),
            'whatsapp_active' => $request->has('whatsapp_active'), // Switch toggle maps to checkbox/has()
        ]);

        return redirect()->route('setting.index')
            ->with('success', 'Pengaturan WhatsApp Fonnte berhasil diperbarui.');
    }

    /**
     * Store new partner category.
     */
    public function storeKategori(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isBKK()) {
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
        if (!auth()->user()->isAdmin() && !auth()->user()->isBKK()) {
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
}
