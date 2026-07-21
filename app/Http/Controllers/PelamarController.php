<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelamarController extends Controller
{
    /**
     * Tampilkan daftar pelamar kerja.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $lowonganId = $request->input('lowongan_kerja_id');
        $status = $request->input('status', 'Pending'); // Default hanya tampilkan yang Pending (belum diproses)

        $pelamars = Pelamar::with('lowonganKerja')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('nomor_telepon', 'like', "%{$search}%")
                      ->orWhere('program_keahlian', 'like', "%{$search}%");
                });
            })
            ->when($lowonganId, function ($query, $lowonganId) {
                $query->where('lowongan_kerja_id', $lowonganId);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $lowongans = LowonganKerja::orderBy('judul', 'asc')->get();

        return view('pages.bkk.pelamar.index', compact('pelamars', 'lowongans', 'search', 'lowonganId', 'status'));
    }

    /**
     * Update status pelamar (Pending/Diterima/Ditolak).
     * Jika status diubah menjadi Diterima:
     * 1. Kirim notifikasi WA ke pelamar Diterima & semua pelamar Ditolak.
     * 2. Pindahkan data pelamar yang Diterima ke tabel AlumniBekerja.
     * 3. Hapus data pelamar dari daftar pelamar agar tidak menumpuk.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        $pelamar = Pelamar::with('lowonganKerja.perusahaanMitra')->findOrFail($id);
        $oldStatus = $pelamar->status;
        $newStatus = $request->status;

        $waService = new \App\Services\WhatsappService();

        if ($newStatus === 'Diterima' && $oldStatus !== 'Diterima') {
            // 1. Update status pelamar ini ke Diterima sementara untuk kirim WA
            $pelamar->update(['status' => 'Diterima']);
            $waService->sendPelamarDiterima($pelamar);

            // 2. Pindahkan data ke Alumni Bekerja jika belum disalin dan Tutup Lowongan Kerja
            $lowongan = $pelamar->lowonganKerja;
            if ($lowongan) {
                // Tutup status lowongan secara otomatis
                $lowongan->update(['status' => 'Tutup']);

                $exists = \App\Models\AlumniBekerja::where('nama_alumni', $pelamar->nama_lengkap)
                    ->where('tahun_lulus', $pelamar->tahun_lulus)
                    ->exists();

                if (!$exists) {
                    \App\Models\AlumniBekerja::create([
                        'nama_alumni' => $pelamar->nama_lengkap,
                        'perusahaan_mitra_id' => $lowongan->perusahaan_mitra_id,
                        'perusahaan_nama' => $lowongan->nama_perusahaan,
                        'jabatan' => $lowongan->posisi,
                        'tanggal_masuk' => now()->toDateString(),
                        'tahun_lulus' => $pelamar->tahun_lulus,
                        'bidang_industri' => $lowongan->perusahaanMitra ? $lowongan->perusahaanMitra->bidang_industri : 'Lainnya',
                        'gaji' => null,
                        'status_pekerjaan' => 'Kontrak',
                    ]);
                }
            }

            // 3. Otomatis Tolak pelamar lain di lowongan yang sama yang masih Pending & Kirim WA Tolak
            $otherPelamars = Pelamar::where('lowongan_kerja_id', $pelamar->lowongan_kerja_id)
                ->where('id', '!=', $pelamar->id)
                ->where('status', 'Pending')
                ->get();

            foreach ($otherPelamars as $other) {
                $other->update(['status' => 'Ditolak']);
                $waService->sendPelamarDitolak($other);

                // Hapus pelamar yang ditolak agar tidak menumpuk
                if ($other->file_cv && Storage::disk('public')->exists($other->file_cv)) {
                    Storage::disk('public')->delete($other->file_cv);
                }
                $other->delete();
            }

            // 4. Hapus pelamar yang diterima ini dari daftar pelamar (karena sudah pindah ke Alumni Bekerja)
            $namaPelamar = $pelamar->nama_lengkap;
            if ($pelamar->file_cv && Storage::disk('public')->exists($pelamar->file_cv)) {
                Storage::disk('public')->delete($pelamar->file_cv);
            }
            $pelamar->delete();

            return back()->with('success', "Pelamar {$namaPelamar} DITERIMA! Notifikasi WA telah dikirim, data resmi dipindahkan ke Data Alumni Bekerja, dan pelamar lainnya otomatis ditolak & dibersihkan dari daftar.");
        }

        // Jika hanya diubah manual ke Ditolak
        if ($newStatus === 'Ditolak') {
            $waService->sendPelamarDitolak($pelamar);

            // Hapus dari daftar pelamar agar tidak menumpuk
            $namaPelamar = $pelamar->nama_lengkap;
            if ($pelamar->file_cv && Storage::disk('public')->exists($pelamar->file_cv)) {
                Storage::disk('public')->delete($pelamar->file_cv);
            }
            $pelamar->delete();

            return back()->with('success', "Pelamar {$namaPelamar} DITOLAK! Notifikasi WA telah dikirim dan data dibersihkan dari daftar pelamar.");
        }

        $pelamar->update(['status' => $newStatus]);

        return back()->with('success', "Status pelamar {$pelamar->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Tampilkan CV pelamar langsung di browser (inline PDF).
     */
    public function viewCv($id)
    {
        $pelamar = Pelamar::findOrFail($id);

        if (!$pelamar->file_cv || !Storage::disk('public')->exists($pelamar->file_cv)) {
            return back()->with('error', 'File CV tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($pelamar->file_cv);
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="CV_' . $pelamar->nama_lengkap . '.pdf"',
        ]);
    }

    /**
     * Download CV pelamar.
     */
    public function downloadCv($id)
    {
        $pelamar = Pelamar::findOrFail($id);

        if (!$pelamar->file_cv || !Storage::disk('public')->exists($pelamar->file_cv)) {
            return back()->with('error', 'File CV tidak ditemukan.');
        }

        return Storage::disk('public')->download($pelamar->file_cv, "CV_{$pelamar->nama_lengkap}.pdf");
    }

    /**
     * Hapus data pelamar.
     */
    public function destroy($id)
    {
        $pelamar = Pelamar::findOrFail($id);

        if ($pelamar->file_cv && Storage::disk('public')->exists($pelamar->file_cv)) {
            Storage::disk('public')->delete($pelamar->file_cv);
        }

        $pelamar->delete();

        return back()->with('success', 'Data pelamar berhasil dihapus.');
    }
}
