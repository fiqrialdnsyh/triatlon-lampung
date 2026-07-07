<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\PersonilController;

Route::get('/', function () {
    return view('welcome');
});

// --- RUTE PELATIHAN ---
Route::get('/pelatihan', [PelatihanController::class, 'index']);
Route::get('/pelatihan/create', [PelatihanController::class, 'create']);
Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');
Route::post('/pelatihan/{id}/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

// Rute persetujuan admin
Route::post('/pendaftaran/{id}/terima', [PendaftaranController::class, 'terima']);
Route::post('/pendaftaran/{id}/tolak', [PendaftaranController::class, 'tolak']);

// Rute untuk mencetak/download tiket pendaftaran peserta
Route::get('/pelatihan/{id}/tiket', [PendaftaranController::class, 'cetakTiket'])->name('pendaftaran.tiket');


// RUTE EDIT & UPDATE (Harus di atas rute {id})
Route::get('/pelatihan/{id}/edit', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
Route::put('/pelatihan/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');

// RUTE DINAMIS DETAIL
Route::get('/pelatihan/{id}', [PelatihanController::class, 'show']);

// --- RUTE AUTENTIKASI ---
Route::get('/login', function () {if (Illuminate\Support\Facades\Auth::check()) {return redirect('/');}return view('auth.login');})->name('login');

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute untuk login Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// RUTE BERITA DINAMIS
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/create', [BeritaController::class, 'create']);
Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');

// Tambahan Rute Manajemen, Edit, Update, dan Delete (Harus di atas rute {id})
Route::get('/berita/kelola', [BeritaController::class, 'kelola'])->name('berita.kelola');
Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

// Rute Pengurus Publik
Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');

// Rute Detail Berita
Route::get('/berita/{id}', [BeritaController::class, 'show']);

// Rute Tulis Berita Baru (Hanya Admin)
Route::get('/berita/create', function () {if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {return redirect('/berita');}return view('berita.create');});

// Rute Manajemen Pengurus (Admin)
Route::get('/pengurus/create', [PengurusController::class, 'create'])->name('pengurus.create');
Route::post('/pengurus', [PengurusController::class, 'store'])->name('pengurus.store');

// Tambahan Rute Manajemen Kontrol (Kelola, Edit, Update, Delete)
Route::get('/pengurus/kelola', [PengurusController::class, 'kelola'])->name('pengurus.kelola');
Route::get('/pengurus/{id}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
Route::put('/pengurus/{id}', [PengurusController::class, 'update'])->name('pengurus.update');
Route::delete('/pengurus/{id}', [PengurusController::class, 'destroy'])->name('pengurus.destroy');

// Rute Publik Personil
Route::get('/personil', [PersonilController::class, 'index'])->name('personil.index');

// Rute Kontrol Manajemen Administrator (CRUD Personil)
Route::get('/personil/create', [PersonilController::class, 'create'])->name('personil.create');
Route::post('/personil', [PersonilController::class, 'store'])->name('personil.store');
Route::get('/personil/kelola', [PersonilController::class, 'kelola'])->name('personil.kelola');
Route::put('/personil/{id}', [PersonilController::class, 'update'])->name('personil.update');
Route::delete('/personil/{id}', [PersonilController::class, 'destroy'])->name('personil.destroy');
