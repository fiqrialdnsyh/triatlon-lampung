<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\EventOpenController;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// 1. RUTE AUTENTIKASI
// ==========================================
Route::get('/login', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        return redirect('/');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk login Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


// ==========================================
// 2. RUTE PELATIHAN
// ==========================================
Route::get('/pelatihan', [PelatihanController::class, 'index']);
Route::get('/pelatihan/create', [PelatihanController::class, 'create']);
Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');

// Manajemen Edit & Update Pelatihan
Route::get('/pelatihan/{id}/edit', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
Route::put('/pelatihan/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');

// Detail Pelatihan (Wildcard {id} HARUS di paling bawah grup Pelatihan)
Route::get('/pelatihan/{id}', [PelatihanController::class, 'show']);

// Rute Pendaftaran & Tiket
Route::post('/pelatihan/{id}/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pelatihan/{id}/tiket', [PendaftaranController::class, 'cetakTiket'])->name('pendaftaran.tiket');
Route::post('/pendaftaran/{id}/terima', [PendaftaranController::class, 'terima']);
Route::post('/pendaftaran/{id}/tolak', [PendaftaranController::class, 'tolak']);


// ==========================================
// 3. RUTE BERITA
// ==========================================
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/create', [BeritaController::class, 'create']);
Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');

// Manajemen Kontrol Berita
Route::get('/berita/kelola', [BeritaController::class, 'kelola'])->name('berita.kelola');
Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

// Detail Berita (Wildcard {id} HARUS di paling bawah grup Berita)
Route::get('/berita/{id}', [BeritaController::class, 'show']);


// ==========================================
// 4. RUTE PENGURUS
// ==========================================
Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
Route::get('/pengurus/create', [PengurusController::class, 'create'])->name('pengurus.create');
Route::post('/pengurus', [PengurusController::class, 'store'])->name('pengurus.store');

// Manajemen Kontrol Pengurus
Route::get('/pengurus/kelola', [PengurusController::class, 'kelola'])->name('pengurus.kelola');
Route::get('/pengurus/{id}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
Route::put('/pengurus/{id}', [PengurusController::class, 'update'])->name('pengurus.update');
Route::delete('/pengurus/{id}', [PengurusController::class, 'destroy'])->name('pengurus.destroy');


// ==========================================
// 5. RUTE SDM (PERSONIL)
// ==========================================
Route::get('/personil', [PersonilController::class, 'index'])->name('personil.index');
Route::get('/personil/create', [PersonilController::class, 'create'])->name('personil.create');
Route::post('/personil', [PersonilController::class, 'store'])->name('personil.store');

// Manajemen Kontrol SDM
Route::get('/personil/kelola', [PersonilController::class, 'kelola'])->name('personil.kelola');
Route::put('/personil/{id}', [PersonilController::class, 'update'])->name('personil.update');
Route::delete('/personil/{id}', [PersonilController::class, 'destroy'])->name('personil.destroy');


// ==========================================
// 6. RUTE EVENT OPEN TOURNAMENT
// ==========================================
Route::get('/event/open', [EventOpenController::class, 'index'])->name('event.open.index');

// Rute Manajemen Sistem Kontrol Akun Admin
Route::get('/event/open/tambah', [EventOpenController::class, 'create'])->name('event.open.create');
Route::post('/event/open/simpan', [EventOpenController::class, 'store'])->name('event.open.store');
Route::get('/event/open/kelola', [EventOpenController::class, 'kelola'])->name('event.open.kelola');
Route::put('/event/open/{id}/update', [EventOpenController::class, 'update'])->name('event.open.update');
Route::delete('/event/open/{id}/hapus', [EventOpenController::class, 'destroy'])->name('event.open.destroy');

// Rute Riwayat Pendaftaran Peserta (Harus Di Atas Rute Slug)
Route::get('/event/open/riwayat', [EventOpenController::class, 'history'])->name('event.open.history')->middleware('auth');
Route::get('/event/open/tiket/{qr_token}', [EventOpenController::class, 'showTiket'])->name('event.open.tiket');

// Rute Verifikasi Pendaftaran Peserta (Harus Di Atas Rute Slug)
Route::post('/event/open/verifikasi/{id}', [EventOpenController::class, 'verifikasi'])->name('event.open.verifikasi');

// Rute Publik Detail Event (Wildcard {slug} HARUS di paling bawah grup Event Open)
Route::get('/event/open/{slug}', [EventOpenController::class, 'show'])->name('event.open.show');

// Rute Pendaftaran Event (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/event/open/{id}/register', [EventOpenController::class, 'register'])->name('event.open.register');
});
