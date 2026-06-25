<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KerjaSamaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PerusahaanMitraController;
use App\Http\Controllers\AlumniBekerjaController;
use App\Http\Controllers\LowonganKerjaController;
use App\Http\Controllers\TracerStudyController;
use App\Http\Controllers\LaporanBkkController;
use App\Http\Controllers\LaporanKerjaSamaController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Kelola Pengguna, Pengaturan, & Notifikasi (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/tambah', [PenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/{id}', [PenggunaController::class, 'show'])->name('pengguna.show');
        Route::get('/pengguna/{id}/ubah', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

        Route::get('/pengaturan', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('setting.update');
        Route::post('/pengaturan/kategori', [SettingController::class, 'storeKategori'])->name('setting.kategori.store');
        Route::delete('/pengaturan/kategori/{id}', [SettingController::class, 'destroyKategori'])->name('setting.kategori.destroy');

        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'readAndRedirect'])->name('notifikasi.read-and-redirect');
        Route::patch('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
        Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
        Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    });

    // Admin & BKK can manage Kerja Sama (tambah/ubah/hapus)
    Route::middleware('role:admin,bkk')->group(function () {
        Route::get('/kerja-sama/tambah', [KerjaSamaController::class, 'create'])->name('kerja-sama.create');
        Route::post('/kerja-sama', [KerjaSamaController::class, 'store'])->name('kerja-sama.store');
        Route::get('/kerja-sama/{id}/ubah', [KerjaSamaController::class, 'edit'])->name('kerja-sama.edit');
        Route::put('/kerja-sama/{id}', [KerjaSamaController::class, 'update'])->name('kerja-sama.update');
        Route::delete('/kerja-sama/{id}', [KerjaSamaController::class, 'destroy'])->name('kerja-sama.destroy');
        Route::post('/kerja-sama/{id}/kirim-wa', [KerjaSamaController::class, 'kirimWhatsapp'])->name('kerja-sama.kirim-wa');

        // BKK - Perusahaan Mitra
        Route::get('/perusahaan-mitra', [PerusahaanMitraController::class, 'index'])->name('perusahaan-mitra.index');
        Route::get('/perusahaan-mitra/tambah', [PerusahaanMitraController::class, 'create'])->name('perusahaan-mitra.create');
        Route::post('/perusahaan-mitra', [PerusahaanMitraController::class, 'store'])->name('perusahaan-mitra.store');
        Route::get('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'show'])->name('perusahaan-mitra.show');
        Route::get('/perusahaan-mitra/{id}/ubah', [PerusahaanMitraController::class, 'edit'])->name('perusahaan-mitra.edit');
        Route::put('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'update'])->name('perusahaan-mitra.update');
        Route::delete('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'destroy'])->name('perusahaan-mitra.destroy');

        // BKK - Alumni Bekerja
        Route::get('/alumni-bekerja', [AlumniBekerjaController::class, 'index'])->name('alumni-bekerja.index');
        Route::get('/alumni-bekerja/tambah', [AlumniBekerjaController::class, 'create'])->name('alumni-bekerja.create');
        Route::post('/alumni-bekerja', [AlumniBekerjaController::class, 'store'])->name('alumni-bekerja.store');
        Route::get('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'show'])->name('alumni-bekerja.show');
        Route::get('/alumni-bekerja/{id}/ubah', [AlumniBekerjaController::class, 'edit'])->name('alumni-bekerja.edit');
        Route::put('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'update'])->name('alumni-bekerja.update');
        Route::delete('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'destroy'])->name('alumni-bekerja.destroy');

        // BKK - Lowongan Kerja
        Route::get('/lowongan-kerja', [LowonganKerjaController::class, 'index'])->name('lowongan-kerja.index');
        Route::get('/lowongan-kerja/tambah', [LowonganKerjaController::class, 'create'])->name('lowongan-kerja.create');
        Route::post('/lowongan-kerja', [LowonganKerjaController::class, 'store'])->name('lowongan-kerja.store');
        Route::get('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'show'])->name('lowongan-kerja.show');
        Route::get('/lowongan-kerja/{id}/ubah', [LowonganKerjaController::class, 'edit'])->name('lowongan-kerja.edit');
        Route::put('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'update'])->name('lowongan-kerja.update');
        Route::delete('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'destroy'])->name('lowongan-kerja.destroy');

        // BKK - Tracer Study
        Route::get('/tracer-study', [TracerStudyController::class, 'index'])->name('tracer-study.index');
        Route::get('/tracer-study/tambah', [TracerStudyController::class, 'create'])->name('tracer-study.create');
        Route::post('/tracer-study', [TracerStudyController::class, 'store'])->name('tracer-study.store');
        Route::get('/tracer-study/{id}/ubah', [TracerStudyController::class, 'edit'])->name('tracer-study.edit');
        Route::put('/tracer-study/{id}', [TracerStudyController::class, 'update'])->name('tracer-study.update');
        Route::delete('/tracer-study/{id}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');

        // BKK - Laporan
        Route::get('/laporan-bkk', [LaporanBkkController::class, 'index'])->name('laporan-bkk.index');

        // Kerja Sama - Laporan
        Route::get('/laporan-kerja-sama', [LaporanKerjaSamaController::class, 'index'])->name('laporan-kerja-sama.index');
    });

    // Kelola Kerja Sama (All Authenticated Users can view/download)
    Route::get('/kerja-sama', [KerjaSamaController::class, 'index'])->name('kerja-sama.index');
    Route::get('/kerja-sama/{id}', [KerjaSamaController::class, 'show'])->name('kerja-sama.show');
    Route::get('/kerja-sama/{id}/unduh', [KerjaSamaController::class, 'download'])->name('kerja-sama.download');
});
