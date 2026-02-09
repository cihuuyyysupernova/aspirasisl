<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Aspirasi Siswa
|--------------------------------------------------------------------------
|
| File ini mendefinisikan semua route web untuk aplikasi aspirasi siswa
| Route dikelompokkan berdasarkan role (guest, siswa, admin)
| Menggunakan middleware untuk autentikasi dan role-based access
|
*/

// ========== GUEST ROUTES (Tanpa autentikasi) ==========
// Route yang bisa diakses tanpa login

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');           // Tampilkan form login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');           // Proses login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');               // Proses logout

// ========== SISWA ROUTES (Role: siswa) ==========
// Route yang hanya bisa diakses oleh siswa
// Middleware: auth (harus login) + role:siswa (harus role siswa)
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    // Dashboard siswa
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');

    // Management laporan siswa
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');    // Form buat laporan
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');          // Simpan laporan baru
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');            // Daftar semua laporan
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');          // Detail laporan
    Route::get('/laporan-saya', [SiswaController::class, 'laporanSaya'])->name('laporan-saya');     // Laporan milik siswa

    // Profile siswa
    Route::get('/profile', [SiswaController::class, 'profile'])->name('profile');                   // Tampilkan profile
    Route::put('/profile', [SiswaController::class, 'updateProfile'])->name('profile.update');      // Update profile
});

// ========== ADMIN ROUTES (Role: admin) ==========
// Route yang hanya bisa diakses oleh admin
// Middleware: auth (harus login) + role:admin (harus role admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Management laporan admin
    Route::get('/laporan', [LaporanController::class, 'adminIndex'])->name('laporan.index');           // Daftar semua laporan
    Route::get('/laporan/{id}', [LaporanController::class, 'adminShow'])->name('laporan.show');         // Detail laporan
    Route::post('/laporan/{id}/feedback', [LaporanController::class, 'addFeedback'])->name('laporan.feedback'); // Tambah feedback
    Route::put('/laporan/{id}/status', [LaporanController::class, 'updateStatus'])->name('laporan.status');   // Update status
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');         // Hapus laporan
    Route::delete('/laporan/batch', [LaporanController::class, 'batchDestroy'])->name('laporan.batchDestroy'); // Hapus batch

    // Management siswa admin
    Route::get('/siswa', [AdminController::class, 'siswaIndex'])->name('siswa.index');                   // Daftar siswa
    Route::get('/siswa/create', [AdminController::class, 'siswaCreate'])->name('siswa.create');           // Form buat siswa
    Route::post('/siswa', [AdminController::class, 'siswaStore'])->name('siswa.store');                 // Simpan siswa baru
    Route::get('/siswa/{id}/edit', [AdminController::class, 'siswaEdit'])->name('siswa.edit');            // Form edit siswa
    Route::put('/siswa/{id}', [AdminController::class, 'siswaUpdate'])->name('siswa.update');             // Update siswa
    Route::delete('/siswa/{id}', [AdminController::class, 'siswaDestroy'])->name('siswa.destroy');         // Hapus siswa

    // Profile admin
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');                        // Tampilkan profile
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');           // Update profile
});
