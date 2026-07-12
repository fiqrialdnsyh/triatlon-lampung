<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\PersonilController;
use App\Http\Controllers\EventOpenController;
use App\Http\Controllers\EventKejurnasController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignKontribusiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ranking', function () {
    return view('ranking.index', [
        'title' => 'Ranking Atlet',
        'deskripsi' => 'Fitur peringkat dan statistik performa atlet sedang dalam tahap pengembangan.',
    ]);
})->name('ranking.index');

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

// Halaman Katalog Publik
Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');

Route::middleware('auth')->group(function () {

    // 1. RUTE STATIS (Wajib berada di paling atas agar tidak tertabrak wildcard {id})
    Route::get('/pelatihan/create', [PelatihanController::class, 'create'])->name('pelatihan.create');
    Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');

    // REVISI: Alamat disamakan menjadi /pelatihan/histori agar cocok dengan tombol di halaman katalog
    Route::get('/pelatihan/histori', [PelatihanController::class, 'history'])->name('pelatihan.history');

    // Check-In QR Admin
    Route::post('/pelatihan/checkin/qr', [PelatihanController::class, 'checkIn'])->name('pelatihan.checkin');


    // 2. RUTE DENGAN WILDCARD TAMBAHAN (Aman ditaruh di tengah)
    Route::get('/pelatihan/checkin/{id}/cetak', [PelatihanController::class, 'printCheckIn'])->name('pelatihan.checkin.print');

    Route::get('/pelatihan/{id}/edit', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
    Route::put('/pelatihan/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');

    // Pendaftaran & Tiket Peserta
    Route::post('/pelatihan/{id}/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pelatihan/{id}/tiket', [PendaftaranController::class, 'cetakTiket'])->name('pendaftaran.tiket');
    Route::post('/pendaftaran/{id}/resubmit', [PendaftaranController::class, 'resubmit'])->name('pendaftaran.resubmit');

    // Verifikasi Dokumen oleh Admin
    Route::post('/pendaftaran/{id}/terima', [PendaftaranController::class, 'terima'])->name('pendaftaran.terima');
    Route::post('/pendaftaran/{id}/tolak', [PendaftaranController::class, 'tolak'])->name('pendaftaran.tolak');
    Route::post('/pendaftaran/{id}/batal', [PendaftaranController::class, 'batal'])->name('pendaftaran.batal');


    // 3. RUTE WILDCARD UTAMA (MUTLAK harus ditaruh di paling bawah grup Pelatihan)
    // Jika rute ini ditaruh di atas, URL seperti /create atau /histori akan rusak karena dianggap sebagai ID
    Route::get('/pelatihan/{id}', [PelatihanController::class, 'show'])->name('pelatihan.show');
});


// ==========================================
// 3. RUTE BERITA
// ==========================================
Route::get('/berita', [BeritaController::class, 'index']);
Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');

Route::middleware('auth')->group(function () {
    // Manajemen Kontrol Berita
    Route::get('/berita/create', [BeritaController::class, 'create']);
    Route::get('/berita/kelola', [BeritaController::class, 'kelola'])->name('berita.kelola');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');
});
// Detail Berita (Wildcard {id} HARUS di paling bawah grup Berita)
Route::get('/berita/{id}', [BeritaController::class, 'show']);


// ==========================================
// 4. RUTE PENGURUS
// ==========================================
Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
Route::post('/pengurus', [PengurusController::class, 'store'])->name('pengurus.store');

Route::middleware('auth')->group(function () {
    // Manajemen Kontrol Pengurus
    Route::get('/pengurus/create', [PengurusController::class, 'create'])->name('pengurus.create');
    Route::get('/pengurus/kelola', [PengurusController::class, 'kelola'])->name('pengurus.kelola');
    Route::get('/pengurus/{id}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
    Route::put('/pengurus/{id}', [PengurusController::class, 'update'])->name('pengurus.update');
    Route::delete('/pengurus/{id}', [PengurusController::class, 'destroy'])->name('pengurus.destroy');
});

// ==========================================
// 5. RUTE SDM (PERSONIL)
// ==========================================
Route::get('/personil', [PersonilController::class, 'index'])->name('personil.index');
Route::get('/personil/create', [PersonilController::class, 'create'])->name('personil.create');
Route::post('/personil', [PersonilController::class, 'store'])->name('personil.store');

Route::middleware('auth')->group(function () {
    // Manajemen Kontrol SDM
    Route::get('/personil/create', [PersonilController::class, 'create'])->name('personil.create');
    Route::get('/personil/kelola', [PersonilController::class, 'kelola'])->name('personil.kelola');
    Route::put('/personil/{id}', [PersonilController::class, 'update'])->name('personil.update');
    Route::delete('/personil/{id}', [PersonilController::class, 'destroy'])->name('personil.destroy');
});

// ==========================================
// 6. RUTE EVENT OPEN TOURNAMENT
// ==========================================
// Rute Tampilan Event Gabungan
Route::get('/event', [App\Http\Controllers\MainEventController::class, 'index'])->name('main_event.index');

Route::middleware('auth')->group(function () {
    // Admin membuat event 1 pintu
    Route::get('/event/create', [App\Http\Controllers\MainEventController::class, 'create'])->name('main_event.create');
    Route::post('/event', [App\Http\Controllers\MainEventController::class, 'store'])->name('main_event.store');
});

// Rute Detail Gabungan
Route::get('/event/{slug}', [App\Http\Controllers\MainEventController::class, 'show'])->name('main_event.show');

Route::get('/event/open', [EventOpenController::class, 'index'])->name('event.open.index');

Route::middleware('auth')->group(function () {
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

    // RUTE UNTUK SCANNER QR ABSENSI
    Route::post('/event/open/checkin/qr', [EventOpenController::class, 'checkIn'])->name('event.open.checkin');

    // RUTE BARU UNTUK CETAK PDF ABSENSI LAPANGAN
    Route::get('/event/open/checkin/{id}/cetak', [EventOpenController::class, 'printCheckIn'])->name('event.open.checkin.print');
});
// Rute Publik Detail Event (Wildcard {slug} HARUS di paling bawah grup Event Open)
Route::get('/event/open/{slug}', [EventOpenController::class, 'show'])->name('event.open.show');


// ==========================================
// 7. RUTE EVENT KEJURNAS
// ==========================================
Route::get('/event/kejurnas', [EventKejurnasController::class, 'index'])->name('event.kejurnas.index');

Route::middleware('auth')->group(function () {
    // RUTE MANAGEMENT EVENT KEJURNAS
    Route::get('/event/kejurnas/tambah', [EventKejurnasController::class, 'create'])->name('event.kejurnas.create');
    Route::post('/event/kejurnas/simpan', [EventKejurnasController::class, 'store'])->name('event.kejurnas.store');

    // RUTE BARU: Histori, Edit, dan Update
    Route::get('/event/kejurnas/histori/pendaftaran', [EventKejurnasController::class, 'history'])->name('event.kejurnas.history');
    Route::get('/event/kejurnas/{id}/edit', [EventKejurnasController::class, 'edit'])->name('event.kejurnas.edit');
    Route::put('/event/kejurnas/{id}', [EventKejurnasController::class, 'update'])->name('event.kejurnas.update');

    Route::post('/event/kejurnas/registrasi/{id}/resubmit', [EventKejurnasController::class, 'resubmit'])->name('event.kejurnas.resubmit');
    Route::get('/event/kejurnas/checkin/{id}/cetak', [EventKejurnasController::class, 'printCheckIn'])->name('event.kejurnas.checkin.print');
    Route::post('/event/kejurnas/{id}/register', [EventKejurnasController::class, 'register'])->name('event.kejurnas.register');
    Route::post('/event/kejurnas/verifikasi/{id}', [EventKejurnasController::class, 'verifikasi'])->name('event.kejurnas.verifikasi');
    Route::post('/event/kejurnas/checkin/qr', [EventKejurnasController::class, 'checkIn'])->name('event.kejurnas.checkin');

    // KONTROL KHUSUS ADMIN: PEMBUATAN AKUN KONTINGEN
    Route::post('/event/kejurnas/buat-akun-kontingen', [EventKejurnasController::class, 'buatAkunKontingen'])->name('event.kejurnas.buat_kontingen');
});

Route::get('/event/kejurnas/{slug}', [EventKejurnasController::class, 'show'])->name('event.kejurnas.show');

// ==========================================
// 8. RUTE VENUE & LOKASI
// ==========================================
Route::get('/venue', [App\Http\Controllers\VenueController::class, 'index'])->name('venue.index');

Route::middleware('auth')->group(function () {
    // Manajemen Venue (Khusus Admin)
    Route::get('/venue/create', [VenueController::class, 'create'])->name('venue.create');
    Route::post('/venue', [App\Http\Controllers\VenueController::class, 'store'])->name('venue.store');

    // Hapus Foto Spesifik pada saat Edit
    Route::delete('/venue/foto/{id}', [VenueController::class, 'destroyFoto'])->name('venue.foto.destroy');

    Route::get('/venue/{id}/edit', [VenueController::class, 'edit'])->name('venue.edit');
    Route::put('/venue/{id}', [VenueController::class, 'update'])->name('venue.update');
    Route::delete('/venue/{id}', [VenueController::class, 'destroy'])->name('venue.destroy');
});

// Wildcard Detail Venue (Wajib paling bawah)
Route::get('/venue/{slug}', [VenueController::class, 'show'])->name('venue.show');

Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
Route::get('/campaign/{slug}', [CampaignController::class, 'show'])->name('campaign.show');

Route::post('/campaign/{campaignId}/kontribusi', [CampaignKontribusiController::class, 'store'])->name('campaign.kontribusi.store');
Route::post('/kontribusi/{id}/terima', [CampaignKontribusiController::class, 'terima'])->name('kontribusi.terima');
Route::post('/kontribusi/{id}/tolak', [CampaignKontribusiController::class, 'tolak'])->name('kontribusi.tolak');

// Rute Pendaftaran Event (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/event/open/{id}/register', [EventOpenController::class, 'register'])->name('event.open.register');
});
