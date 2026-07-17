<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
use App\Http\Controllers\MainEventController;

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
// 1. RUTE AUTENTIKASI & KEAMANAN
// ==========================================
Route::get('/login', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        return redirect('/');
    }
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        return redirect('/');
    }
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Login Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Akses File Terlindungi (Membutuhkan Login Dasar)
Route::middleware('auth')->group(function () {
    Route::get('/secure-file/{path}', [App\Http\Controllers\SecureFileController::class, 'show'])
        ->where('path', '.*')
        ->name('secure.file');
});


// ==========================================
// 2. RUTE PELATIHAN
// ==========================================
// Publik
Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');

// Peserta / User Login
Route::middleware('auth')->group(function () {
    Route::get('/pelatihan/histori', [PelatihanController::class, 'history'])->name('pelatihan.history');
    Route::post('/pelatihan/{id}/daftar', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pelatihan/{id}/tiket', [PendaftaranController::class, 'cetakTiket'])->name('pendaftaran.tiket');
    Route::post('/pendaftaran/{id}/resubmit', [PendaftaranController::class, 'resubmit'])->name('pendaftaran.resubmit');
});

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/pelatihan/create', [PelatihanController::class, 'create'])->name('pelatihan.create');
    Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');
    Route::get('/pelatihan/{id}/edit', [PelatihanController::class, 'edit'])->name('pelatihan.edit');
    Route::put('/pelatihan/{id}', [PelatihanController::class, 'update'])->name('pelatihan.update');

    // Verifikasi & Check-In
    Route::post('/pelatihan/checkin/qr', [PelatihanController::class, 'checkIn'])->name('pelatihan.checkin');
    Route::get('/pelatihan/checkin/{id}/cetak', [PelatihanController::class, 'printCheckIn'])->name('pelatihan.checkin.print');
    Route::post('/pendaftaran/{id}/terima', [PendaftaranController::class, 'terima'])->name('pendaftaran.terima');
    Route::post('/pendaftaran/{id}/tolak', [PendaftaranController::class, 'tolak'])->name('pendaftaran.tolak');
    Route::post('/pendaftaran/{id}/batal', [PendaftaranController::class, 'batal'])->name('pendaftaran.batal');
});

// Wildcard Detail Pelatihan (Wajib paling bawah di grup Pelatihan)
Route::get('/pelatihan/{id}', [PelatihanController::class, 'show'])->name('pelatihan.show');


// ==========================================
// 3. RUTE BERITA
// ==========================================
// Publik
Route::get('/berita', [BeritaController::class, 'index']);

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/berita/create', [BeritaController::class, 'create']);
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/kelola', [BeritaController::class, 'kelola'])->name('berita.kelola');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');
});

// Wildcard Detail Berita (Wajib paling bawah di grup Berita)
Route::get('/berita/{id}', [BeritaController::class, 'show']);


// ==========================================
// 4. RUTE PENGURUS
// ==========================================
// Publik
Route::get('/pengurus', [PengurusController::class, 'index'])->name('pengurus.index');
Route::get('/pengurus/cabang/{slug}', [\App\Http\Controllers\PengurusController::class, 'showCabang'])->name('pengurus.cabang.show');

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/pengurus/create', [PengurusController::class, 'create'])->name('pengurus.create');
    Route::post('/pengurus', [PengurusController::class, 'store'])->name('pengurus.store');
    Route::get('/pengurus/kelola', [PengurusController::class, 'kelola'])->name('pengurus.kelola');
    Route::get('/pengurus/{id}/edit', [PengurusController::class, 'edit'])->name('pengurus.edit');
    Route::put('/pengurus/{id}', [PengurusController::class, 'update'])->name('pengurus.update');
    Route::delete('/pengurus/{id}', [PengurusController::class, 'destroy'])->name('pengurus.destroy');
});


// ==========================================
// 5. RUTE SDM (PERSONIL)
// ==========================================
// Publik
Route::get('/personil', [PersonilController::class, 'index'])->name('personil.index');

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/personil/create', [PersonilController::class, 'create'])->name('personil.create');
    Route::post('/personil', [PersonilController::class, 'store'])->name('personil.store');
    Route::get('/personil/kelola', [PersonilController::class, 'kelola'])->name('personil.kelola');
    Route::put('/personil/{id}', [PersonilController::class, 'update'])->name('personil.update');
    Route::delete('/personil/{id}', [PersonilController::class, 'destroy'])->name('personil.destroy');
});


// ==========================================
// 6. RUTE MASTER EVENT GABUNGAN
// ==========================================
// Publik
Route::get('/event', [MainEventController::class, 'index'])->name('main_event.index');

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/event/create', [MainEventController::class, 'create'])->name('main_event.create');
    Route::post('/event', [MainEventController::class, 'store'])->name('main_event.store');
    Route::post('/event/buat-akun-kontingen', [MainEventController::class, 'buatAkunKontingen'])->name('main_event.buat_kontingen');
});

// Wildcard Detail Master Event
Route::get('/event/{slug}', [MainEventController::class, 'show'])->name('main_event.show');


// ==========================================
// 7. RUTE EVENT OPEN TOURNAMENT
// ==========================================
// Publik
Route::get('/event/open', [EventOpenController::class, 'index'])->name('event.open.index');

// Peserta / User Login
Route::middleware('auth')->group(function () {
    Route::get('/event/open/riwayat', [EventOpenController::class, 'history'])->name('event.open.history');
    Route::get('/event/open/tiket/{qr_token}', [EventOpenController::class, 'showTiket'])->name('event.open.tiket');
    Route::post('/event/open/{id}/register', [EventOpenController::class, 'register'])->name('event.open.register');
});

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/event/open/tambah', [EventOpenController::class, 'create'])->name('event.open.create');
    Route::post('/event/open/simpan', [EventOpenController::class, 'store'])->name('event.open.store');
    Route::get('/event/open/kelola', [EventOpenController::class, 'kelola'])->name('event.open.kelola');
    Route::put('/event/open/{id}/update', [EventOpenController::class, 'update'])->name('event.open.update');
    Route::delete('/event/open/{id}/hapus', [EventOpenController::class, 'destroy'])->name('event.open.destroy');

    // Verifikasi & Check-In
    Route::post('/event/open/verifikasi/{id}', [EventOpenController::class, 'verifikasi'])->name('event.open.verifikasi');
    Route::post('/event/open/checkin/qr', [EventOpenController::class, 'checkIn'])->name('event.open.checkin');
    Route::get('/event/open/checkin/{id}/cetak', [EventOpenController::class, 'printCheckIn'])->name('event.open.checkin.print');
});

// Wildcard Detail Event Open
Route::get('/event/open/{slug}', [EventOpenController::class, 'show'])->name('event.open.show');


// ==========================================
// 8. RUTE EVENT KEJURNAS
// ==========================================
// Publik
Route::get('/event/kejurnas', [EventKejurnasController::class, 'index'])->name('event.kejurnas.index');

// Kontingen / User Login
Route::middleware('auth')->group(function () {
    Route::get('/event/kejurnas/histori/pendaftaran', [EventKejurnasController::class, 'history'])->name('event.kejurnas.history');
    Route::post('/event/kejurnas/{id}/register', [EventKejurnasController::class, 'register'])->name('event.kejurnas.register');
    Route::post('/event/kejurnas/registrasi/{id}/resubmit', [EventKejurnasController::class, 'resubmit'])->name('event.kejurnas.resubmit');
});

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/event/kejurnas/tambah', [EventKejurnasController::class, 'create'])->name('event.kejurnas.create');
    Route::post('/event/kejurnas/simpan', [EventKejurnasController::class, 'store'])->name('event.kejurnas.store');
    Route::get('/event/kejurnas/{id}/edit', [EventKejurnasController::class, 'edit'])->name('event.kejurnas.edit');
    Route::put('/event/kejurnas/{id}', [EventKejurnasController::class, 'update'])->name('event.kejurnas.update');

    // Verifikasi & Check-In
    Route::post('/event/kejurnas/verifikasi/{id}', [EventKejurnasController::class, 'verifikasi'])->name('event.kejurnas.verifikasi');
    Route::post('/event/kejurnas/checkin/qr', [EventKejurnasController::class, 'checkIn'])->name('event.kejurnas.checkin');
    Route::get('/event/kejurnas/checkin/{id}/cetak', [EventKejurnasController::class, 'printCheckIn'])->name('event.kejurnas.checkin.print');

    // Akses Manajemen Tambahan
    Route::post('/event/kejurnas/buat-akun-kontingen', [EventKejurnasController::class, 'buatAkunKontingen'])->name('event.kejurnas.buat_kontingen');
});

// Wildcard Detail Event Kejurnas
Route::get('/event/kejurnas/{slug}', [EventKejurnasController::class, 'show'])->name('event.kejurnas.show');


// ==========================================
// 9. RUTE VENUE & LOKASI
// ==========================================
// Publik
Route::get('/venue', [VenueController::class, 'index'])->name('venue.index');

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/venue/create', [VenueController::class, 'create'])->name('venue.create');
    Route::post('/venue', [VenueController::class, 'store'])->name('venue.store');
    Route::get('/venue/{id}/edit', [VenueController::class, 'edit'])->name('venue.edit');
    Route::put('/venue/{id}', [VenueController::class, 'update'])->name('venue.update');
    Route::delete('/venue/{id}', [VenueController::class, 'destroy'])->name('venue.destroy');
    Route::delete('/venue/foto/{id}', [VenueController::class, 'destroyFoto'])->name('venue.foto.destroy');
});

// Wildcard Detail Venue
Route::get('/venue/{slug}', [VenueController::class, 'show'])->name('venue.show');


// ==========================================
// 10. RUTE CAMPAIGN & DONASI
// ==========================================
// Publik
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign.index');

// User Login (Penyumbang)
Route::middleware('auth')->group(function () {
    Route::post('/campaign/{campaignId}/kontribusi', [CampaignKontribusiController::class, 'store'])->name('campaign.kontribusi.store');
});

// Khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/campaign/create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign', [CampaignController::class, 'store'])->name('campaign.store');
    Route::post('/kontribusi/{id}/terima', [CampaignKontribusiController::class, 'terima'])->name('kontribusi.terima');
    Route::post('/kontribusi/{id}/tolak', [CampaignKontribusiController::class, 'tolak'])->name('kontribusi.tolak');
});

// Wildcard Detail Campaign
Route::get('/campaign/{slug}', [CampaignController::class, 'show'])->name('campaign.show');


// ==========================================
// 11. VERIFIKASI EMAIL
// ==========================================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

