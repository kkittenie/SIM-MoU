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
use App\Http\Controllers\AlumniKuliahController;
use App\Http\Controllers\LaporanBKController;
use App\Http\Controllers\LowonganKerjaController;
use App\Http\Controllers\TracerStudyController;
use App\Http\Controllers\LaporanBkkController;
use App\Http\Controllers\LaporanKerjaSamaController;
use App\Http\Controllers\TracerKuliahController;
use App\Http\Controllers\UniversitasController;

// ════════════════════════════════════════════════
// GUEST ROUTES
// ════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ════════════════════════════════════════════════
// AUTHENTICATED ROUTES
// ════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (auto-detect role)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Admin Only
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

        // ╔═══ Universitas CRUD ═══╗
        Route::get('/universitas', [UniversitasController::class, 'index'])->name('universitas.index');
        Route::get('/universitas/tambah', [UniversitasController::class, 'create'])->name('universitas.create');
        Route::post('/universitas', [UniversitasController::class, 'store'])->name('universitas.store');
        Route::get('/universitas/{universitas}', [UniversitasController::class, 'show'])->name('universitas.show');
        Route::get('/universitas/{universitas}/ubah', [UniversitasController::class, 'edit'])->name('universitas.edit');
        Route::put('/universitas/{universitas}', [UniversitasController::class, 'update'])->name('universitas.update');
        Route::delete('/universitas/{universitas}', [UniversitasController::class, 'destroy'])->name('universitas.destroy');

        //notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'readAndRedirect'])->name('notifikasi.read-and-redirect');
        Route::patch('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
        Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
        Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

        Route::get('/kerja-sama/tambah', [KerjaSamaController::class, 'create'])->name('kerja-sama.create');
        Route::post('/kerja-sama', [KerjaSamaController::class, 'store'])->name('kerja-sama.store');
        Route::get('/kerja-sama/{id}/ubah', [KerjaSamaController::class, 'edit'])->name('kerja-sama.edit');
        Route::put('/kerja-sama/{id}', [KerjaSamaController::class, 'update'])->name('kerja-sama.update');
        Route::delete('/kerja-sama/{id}', [KerjaSamaController::class, 'destroy'])->name('kerja-sama.destroy');
        Route::post('/kerja-sama/{id}/kirim-wa', [KerjaSamaController::class, 'kirimWhatsapp'])->name('kerja-sama.kirim-wa');

        // Perusahaan Mitra
        Route::get('/perusahaan-mitra', [PerusahaanMitraController::class, 'index'])->name('perusahaan-mitra.index');
        Route::get('/perusahaan-mitra/tambah', [PerusahaanMitraController::class, 'create'])->name('perusahaan-mitra.create');
        Route::post('/perusahaan-mitra', [PerusahaanMitraController::class, 'store'])->name('perusahaan-mitra.store');
        Route::get('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'show'])->name('perusahaan-mitra.show');
        Route::get('/perusahaan-mitra/{id}/ubah', [PerusahaanMitraController::class, 'edit'])->name('perusahaan-mitra.edit');
        Route::put('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'update'])->name('perusahaan-mitra.update');
        Route::delete('/perusahaan-mitra/{id}', [PerusahaanMitraController::class, 'destroy'])->name('perusahaan-mitra.destroy');

        // Kerja Sama - Laporan
        Route::get('/laporan-kerja-sama', [LaporanKerjaSamaController::class, 'index'])->name('laporan-kerja-sama.index');
    });

    // Admin & BKK
    Route::middleware('role:admin,bkk')->group(function () {
        Route::get('/alumni-bekerja', [AlumniBekerjaController::class, 'index'])->name('alumni-bekerja.index');
        Route::get('/alumni-bekerja/tambah', [AlumniBekerjaController::class, 'create'])->name('alumni-bekerja.create');
        Route::post('/alumni-bekerja', [AlumniBekerjaController::class, 'store'])->name('alumni-bekerja.store');
        Route::get('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'show'])->name('alumni-bekerja.show');
        Route::get('/alumni-bekerja/{id}/ubah', [AlumniBekerjaController::class, 'edit'])->name('alumni-bekerja.edit');
        Route::put('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'update'])->name('alumni-bekerja.update');
        Route::delete('/alumni-bekerja/{id}', [AlumniBekerjaController::class, 'destroy'])->name('alumni-bekerja.destroy');

        Route::get('/lowongan-kerja', [LowonganKerjaController::class, 'index'])->name('lowongan-kerja.index');
        Route::get('/lowongan-kerja/tambah', [LowonganKerjaController::class, 'create'])->name('lowongan-kerja.create');
        Route::post('/lowongan-kerja', [LowonganKerjaController::class, 'store'])->name('lowongan-kerja.store');
        Route::get('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'show'])->name('lowongan-kerja.show');
        Route::get('/lowongan-kerja/{id}/ubah', [LowonganKerjaController::class, 'edit'])->name('lowongan-kerja.edit');
        Route::put('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'update'])->name('lowongan-kerja.update');
        Route::delete('/lowongan-kerja/{id}', [LowonganKerjaController::class, 'destroy'])->name('lowongan-kerja.destroy');

        Route::get('/tracer-study', [TracerStudyController::class, 'index'])->name('tracer-study.index');
        Route::get('/tracer-study/tambah', [TracerStudyController::class, 'create'])->name('tracer-study.create');
        Route::post('/tracer-study', [TracerStudyController::class, 'store'])->name('tracer-study.store');
        Route::get('/tracer-study/{id}/ubah', [TracerStudyController::class, 'edit'])->name('tracer-study.edit');
        Route::put('/tracer-study/{id}', [TracerStudyController::class, 'update'])->name('tracer-study.update');
        Route::delete('/tracer-study/{id}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');
        Route::post('/tracer-study/bulk-delete', [TracerStudyController::class, 'bulkDelete'])->name('tracer-study.bulk-delete');

        Route::get('/laporan-bkk', [LaporanBkkController::class, 'index'])->name('laporan-bkk.index');
    });

    // Kerja Sama (semua user)
    Route::get('/kerja-sama', [KerjaSamaController::class, 'index'])->name('kerja-sama.index');
    Route::get('/kerja-sama/{id}', [KerjaSamaController::class, 'show'])->name('kerja-sama.show');
    Route::get('/kerja-sama/{id}/unduh', [KerjaSamaController::class, 'download'])->name('kerja-sama.download');

    // BK Only
    Route::middleware('role:admin,bk')->group(function () {
        // Index & List
        Route::get('/bk/alumni-kuliah', [AlumniKuliahController::class, 'index'])->name('bk.alumni-kuliah.index');

        // Create
        Route::get('/bk/alumni-kuliah/tambah', [AlumniKuliahController::class, 'create'])->name('bk.alumni-kuliah.create');
        Route::post('/bk/alumni-kuliah', [AlumniKuliahController::class, 'store'])->name('bk.alumni-kuliah.store');

        Route::get('/bk/alumni-kuliah/download-template', [AlumniKuliahController::class, 'downloadTemplate'])->name('bk.alumni-kuliah.download-template');
        Route::post('/bk/alumni-kuliah/import', [AlumniKuliahController::class, 'import'])->name('bk.alumni-kuliah.import');

        // Export (harus SEBELUM route dengan parameter)
        Route::get('/bk/alumni-kuliah/export/excel', [AlumniKuliahController::class, 'exportExcel'])->name('bk.alumni-kuliah.export-excel');

        // Show, Edit, Update, Delete (pakai {alumniKuliah} - Model Binding)
        Route::get('/bk/alumni-kuliah/{alumniKuliah}', [AlumniKuliahController::class, 'show'])->name('bk.alumni-kuliah.show');
        Route::get('/bk/alumni-kuliah/{alumniKuliah}/ubah', [AlumniKuliahController::class, 'edit'])->name('bk.alumni-kuliah.edit');
        Route::put('/bk/alumni-kuliah/{alumniKuliah}', [AlumniKuliahController::class, 'update'])->name('bk.alumni-kuliah.update');
        Route::delete('/bk/alumni-kuliah/{alumniKuliah}', [AlumniKuliahController::class, 'destroy'])->name('bk.alumni-kuliah.destroy');

        // Bulk Delete
        Route::post('/bk/alumni-kuliah/bulk-delete', [AlumniKuliahController::class, 'bulkDelete'])->name('bk.alumni-kuliah.bulk-delete');

        // ╔═══ Universitas - BK (Read-Only) ═══╗
        Route::get('/bk/universitas', [UniversitasController::class, 'index'])->name('bk.universitas.index');
        Route::get('/bk/universitas/{universitas}', [UniversitasController::class, 'show'])->name('bk.universitas.show');

        Route::get('/bk/tracer-kuliah', [TracerKuliahController::class, 'index'])->name('bk.tracer-kuliah.index');
        Route::get('/bk/tracer-kuliah/tambah', [TracerKuliahController::class, 'create'])->name('bk.tracer-kuliah.create');
        Route::post('/bk/tracer-kuliah', [TracerKuliahController::class, 'store'])->name('bk.tracer-kuliah.store');
        Route::get('/bk/tracer-kuliah/{tracerKuliah}', [TracerKuliahController::class, 'show'])->name('bk.tracer-kuliah.show');
        Route::get('/bk/tracer-kuliah/{tracerKuliah}/ubah', [TracerKuliahController::class, 'edit'])->name('bk.tracer-kuliah.edit');
        Route::put('/bk/tracer-kuliah/{tracerKuliah}', [TracerKuliahController::class, 'update'])->name('bk.tracer-kuliah.update');
        Route::delete('/bk/tracer-kuliah/{tracerKuliah}', [TracerKuliahController::class, 'destroy'])->name('bk.tracer-kuliah.destroy');
        Route::post('/bk/tracer-kuliah/bulk-delete', [TracerKuliahController::class, 'bulkDelete'])->name('bk.tracer-kuliah.bulk-delete');

        Route::get('/bk/laporan', [LaporanBKController::class, 'index'])->name('bk.laporan.index');
    });
});
