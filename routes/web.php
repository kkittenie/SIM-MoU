<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KerjaSamaController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SettingController;

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

    // Kelola Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'readAndRedirect'])->name('notifikasi.read-and-redirect');
    Route::patch('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

    // Kelola Pengguna (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/tambah', [PenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/{id}', [PenggunaController::class, 'show'])->name('pengguna.show');
        Route::get('/pengguna/{id}/ubah', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    });

    // Admin & BKK can manage Kerja Sama (tambah/ubah/hapus) & Settings
    Route::middleware('role:admin,bkk')->group(function () {
        Route::get('/kerja-sama/tambah', [KerjaSamaController::class, 'create'])->name('kerja-sama.create');
        Route::post('/kerja-sama', [KerjaSamaController::class, 'store'])->name('kerja-sama.store');
        Route::get('/kerja-sama/{id}/ubah', [KerjaSamaController::class, 'edit'])->name('kerja-sama.edit');
        Route::put('/kerja-sama/{id}', [KerjaSamaController::class, 'update'])->name('kerja-sama.update');
        Route::delete('/kerja-sama/{id}', [KerjaSamaController::class, 'destroy'])->name('kerja-sama.destroy');
        Route::post('/kerja-sama/{id}/kirim-wa', [KerjaSamaController::class, 'kirimWhatsapp'])->name('kerja-sama.kirim-wa');

        Route::get('/pengaturan', [SettingController::class, 'index'])->name('setting.index');
        Route::put('/pengaturan', [SettingController::class, 'update'])->name('setting.update');
        Route::post('/pengaturan/kategori', [SettingController::class, 'storeKategori'])->name('setting.kategori.store');
        Route::delete('/pengaturan/kategori/{id}', [SettingController::class, 'destroyKategori'])->name('setting.kategori.destroy');
    });

    // Kelola Kerja Sama (All Authenticated Users can view/download)
    Route::get('/kerja-sama', [KerjaSamaController::class, 'index'])->name('kerja-sama.index');
    Route::get('/kerja-sama/{id}', [KerjaSamaController::class, 'show'])->name('kerja-sama.show');
    Route::get('/kerja-sama/{id}/unduh', [KerjaSamaController::class, 'download'])->name('kerja-sama.download');
});
