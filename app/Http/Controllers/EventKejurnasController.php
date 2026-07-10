<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EventKejurnasController extends Controller
{
    public function index()
    {
        $events = Event::where('tipe', 'Kejurnas')->orderBy('tanggal_pelaksanaan', 'desc')->get();
        // Mengambil daftar seluruh akun ber-role kontingen untuk dipantau admin
        $kontingens = User::where('role', 'kontingen')->orderBy('name', 'asc')->get();

        return view('event.kejurnas.index', compact('events', 'kontingens'));
    }

    // Proses Admin Membuat Akun Kontingen Baru
    public function buatAkunKontingen(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kontingen'
        ]);

        return redirect()->back()->with('success', 'Akun Kontingen Resmi baru berhasil dibuat dan didaftarkan.');
    }

    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        return view('event.kejurnas.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:today',
            'lokasi' => 'required|string|max:255',
            'kategori_lomba' => 'required|string|max:255',
            'kuota_maksimal' => 'required|numeric|min:1',
            'batas_pendaftaran' => 'required|date|before_or_equal:tanggal_pelaksanaan',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thb_file' => 'nullable|mimes:pdf|max:10240',
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'atas_nama' => 'required|string',
            'link_wa_grup' => 'required|url',
            'nama_golongan' => 'required|array|min:1',
            'biaya_golongan' => 'required|array|min:1',
        ]);

        $skemaBiaya = [];
        foreach ($request->nama_golongan as $index => $nama) {
            if (!empty($nama)) {
                $skemaBiaya[] = [
                    'nama' => $nama,
                    'biaya' => (int) $request->biaya_golongan[$index]
                ];
            }
        }

        Event::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . time(),
            'deskripsi' => $request->deskripsi,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'lokasi' => $request->lokasi,
            'kategori_lomba' => $request->kategori_lomba,
            'kuota_maksimal' => $request->kuota_maksimal,
            'batas_pendaftaran' => Carbon::parse($request->batas_pendaftaran),
            'poster' => $request->hasFile('poster') ? 'uploads/event/poster/' . $this->uploadFile($request->file('poster'), 'poster', $request->judul) : null,
            'thb_file' => $request->hasFile('thb_file') ? 'uploads/event/thb/' . $this->uploadFile($request->file('thb_file'), 'thb', $request->judul) : null,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'link_wa_grup' => $request->link_wa_grup,
            'skema_biaya' => json_encode($skemaBiaya),
            'tipe' => 'Kejurnas',
            'status' => 'Buka'
        ]);

        return redirect()->route('event.kejurnas.index')->with('success', 'Event Kejurnas baru berhasil diterbitkan.');
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('tipe', 'Kejurnas')->firstOrFail();

        $kuotaTerisi = EventRegistration::where('event_id', $event->id)
                                        ->whereIn('status_pembayaran', ['Menunggu', 'Valid'])
                                        ->count();

        $groupedRegistrations = [];
        $allRegistrations = collect(); // Inisialisasi koleksi kosong sebagai nilai bawaan

        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            // Mengambil semua data secara rata (flat) untuk tabel Check-In
            $allRegistrations = EventRegistration::where('event_id', $event->id)
                                            ->with('user')
                                            ->latest()
                                            ->get();

            // Mengelompokkan data yang sama berdasarkan akun untuk tabel Verifikasi Kontingen
            $groupedRegistrations = $allRegistrations->groupBy('user_id');
        }

        $myAtletRegistrations = [];
        if (auth()->check() && auth()->user()->role === 'kontingen') {
            $myAtletRegistrations = EventRegistration::where('event_id', $event->id)
                                                    ->where('user_id', auth()->id())
                                                    ->latest()
                                                    ->get();
        }

        // Mengirimkan semua variabel yang dibutuhkan ke Blade View
        return view('event.kejurnas.show', compact('event', 'kuotaTerisi', 'allRegistrations', 'groupedRegistrations', 'myAtletRegistrations'));
    }

    public function history()
    {
        if (!auth()->check() || auth()->user()->role !== 'kontingen') return abort(403);
        $registrations = EventRegistration::where('user_id', auth()->id())->with('event')->latest()->get();
        return view('event.kejurnas.history', compact('registrations'));
    }

    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        $event = Event::findOrFail($id);
        return view('event.kejurnas.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $event = Event::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:today',
            'lokasi' => 'required|string|max:255',
            'kategori_lomba' => 'required|string|max:255',
            'kuota_maksimal' => 'required|numeric|min:1',
            'batas_pendaftaran' => 'required|date|before_or_equal:tanggal_pelaksanaan',
            'status' => 'required|in:Buka,Tutup,Selesai',
        ]);

        $event->judul = $request->judul;
        $event->deskripsi = $request->deskripsi;
        $event->tanggal_pelaksanaan = $request->tanggal_pelaksanaan;
        $event->lokasi = $request->lokasi;
        $event->kategori_lomba = $request->kategori_lomba;
        $event->kuota_maksimal = $request->kuota_maksimal;
        $event->batas_pendaftaran = Carbon::parse($request->batas_pendaftaran);
        $event->status = $request->status;
        $event->save();

        return redirect()->route('event.kejurnas.index')->with('success', 'Master Event Kejurnas berhasil diperbarui.');
    }

    public function register(Request $request, $id)
    {
        // VALIDASI AKSES: Hanya user dengan role 'kontingen' buatan admin yang boleh submit form
        if (!auth()->check() || auth()->user()->role !== 'kontingen') {
            return abort(403, 'Hanya akun kontingen resmi yang dapat mendaftarkan atlet pada Event Kejurnas.');
        }

        $event = Event::findOrFail($id);

        // HITUNG KUOTA SAAT INI
        $kuotaTerisi = EventRegistration::where('event_id', $event->id)
                                        ->whereIn('status_pembayaran', ['Menunggu', 'Valid'])
                                        ->count();

        // 1. VALIDASI KUOTA PENUH (OTOMATIS TUTUP EVENT)
        if ($kuotaTerisi >= $event->kuota_maksimal) {
            if ($event->status === 'Buka') {
                $event->status = 'Tutup';
                $event->save();
            }
            return redirect()->back()->withErrors(['kuota' => 'Registrasi ditolak. Kuota atlet untuk delegasi ini telah penuh secara keseluruhan.']);
        }

        // 2. VALIDASI STATUS MANUAL
        if ($event->status !== 'Buka') {
            return redirect()->back()->withErrors(['status' => 'Registrasi ditolak. Pendaftaran event telah ditutup oleh panitia.']);
        }

        // 3. VALIDASI WAKTU KADALUARSA
        if (Carbon::now()->greaterThan(Carbon::parse($event->batas_pendaftaran))) {
            return redirect()->back()->withErrors(['waktu' => 'Registrasi ditolak. Batas waktu pendaftaran telah berakhir.']);
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_ktp' => 'required|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Putra,Putri',
            'golongan_darah' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-,Tidak Tahu',
            'alamat' => 'required|string',
            'asal_daerah' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'bib_name' => 'required|string|max:15',
            'kategori_lomba' => 'required|string|max:255',
            'golongan_biaya' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $usiaAktual = Carbon::parse($request->tanggal_lahir)->age;
        $skemaBiaya = json_decode($event->skema_biaya, true);
        $nominalBayar = 0;
        foreach ($skemaBiaya as $skema) {
            if ($skema['nama'] === $request->golongan_biaya) {
                $nominalBayar = $skema['biaya'];
                break;
            }
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_kejurnas_tf_' . auth()->id() . '_' . rand(100,999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/pembayaran'), $filename);
            $buktiPath = 'uploads/event/pembayaran/' . $filename;
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(), // ID Akun Kontingen penanggung jawab
            'nama_lengkap' => $request->nama_lengkap,
            'nomor_ktp' => $request->nomor_ktp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'usia' => $usiaAktual,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah' => $request->golongan_darah,
            'alamat' => $request->alamat,
            'asal_daerah' => $request->asal_daerah,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'bib_name' => strtoupper($request->bib_name),
            'kategori_lomba' => $request->kategori_lomba,
            'golongan_biaya' => $request->golongan_biaya,
            'nominal_bayar' => $nominalBayar,
            'bukti_transfer' => $buktiPath,
            'qr_token' => 'FTI-KJN-' . strtoupper(Str::random(4)) . '-' . time(),
            'status_pembayaran' => 'Menunggu'
        ]);

        return redirect()->back()->with('success', 'Atlet baru berhasil didaftarkan ke dalam kontingen Anda.');
    }

    private function uploadFile($file, $folder, $title) {
        $filename = time() . '_' . $folder . '_' . Str::slug($title) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/event/' . $folder), $filename);
        return $filename;
    }

    // Menampilkan Halaman Khusus Cetak Laporan Check-In Kejurnas (PDF)
    public function printCheckIn($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return abort(403);
        }

        $event = Event::findOrFail($id);

        // Mengambil pendaftar yang sudah absen, beserta relasi akun kontingennya
        $checkins = EventRegistration::where('event_id', $event->id)
                        ->whereNotNull('waktu_checkin')
                        ->with('user')
                        ->orderBy('waktu_checkin', 'asc')
                        ->get();

        return view('event.kejurnas.print-checkin', compact('event', 'checkins'));
    }

    // Memproses Verifikasi Status Pembayaran & Reset (Khusus Admin untuk Kejurnas)
    public function verifikasi(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return abort(403);
        }

        $registration = EventRegistration::findOrFail($id);

        $request->validate([
            'status_pembayaran' => 'required|in:Valid,Ditolak,Menunggu',
            'pesan_penolakan' => 'nullable|string|max:500'
        ]);

        $registration->status_pembayaran = $request->status_pembayaran;

        if ($request->status_pembayaran === 'Ditolak') {
            // Jika admin tidak mengisi alasan penolakan, gunakan alasan bawaan ini
            $registration->pesan_penolakan = $request->pesan_penolakan ?? 'Bukti transaksi tidak valid atau tidak sesuai nominal yang ditetapkan.';
        } else {
            $registration->pesan_penolakan = null;
        }

        $registration->save();
        return redirect()->back()->with('success', 'Status validasi atlet kontingen berhasil diperbarui.');
    }

    // Memproses Data dari Scanner QR Code Kamera Admin Kejurnas dengan Validasi Nomor Pertandingan
    public function checkIn(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $token = $request->qr_token;
        $eventId = $request->event_id; // Menerima parameter ID Event Kejurnas dari halaman aktif

        // Langkah 1: Cari pendaftaran yang COCOK dengan Token QR sekaligus ID Event Kejurnas ini
        $registration = EventRegistration::where('qr_token', $token)
                                          ->where('event_id', $eventId)
                                          ->first();

        if (!$registration) {
            // Langkah 2: Jika tidak cocok, cek apakah token ini sebenarnya terdaftar di nomor pertandingan/event lain
            $crossRegistration = EventRegistration::where('qr_token', $token)->with('event')->first();

            if ($crossRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'SALAH MEJA / NOMOR PERTANDINGAN! Atlet ini terdaftar pada kejuaraan: "' . $crossRegistration->event->judul . '". Silakan arahkan atlet ke meja scanner nomor pertandingan yang sesuai.'
                ]);
            }

            return response()->json(['success' => false, 'message' => 'QR Code tidak terekam dalam sistem kejuaraan nasional.']);
        }

        // Langkah 3: Validasi status verifikasi berkas kontingen
        if ($registration->status_pembayaran !== 'Valid') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran atlet ini belum disetujui/ditolak oleh bendahara pelaksana.']);
        }

        // Langkah 4: Validasi duplikasi absensi kedatangan lapangan
        if ($registration->waktu_checkin !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Atlet dari kontingen ini sudah melakukan absensi kedatangan sebelumnya pada jam: ' . \Carbon\Carbon::parse($registration->waktu_checkin)->format('d/m/Y H:i:s')
            ]);
        }

        // Catat waktu kehadiran mutlak
        $registration->waktu_checkin = Carbon::now();
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Check-In Kejurnas!',
            'data' => [
                'nama' => $registration->nama_lengkap,
                'bib' => $registration->bib_name,
                'kategori' => $registration->kategori_lomba,
                'asal' => $registration->asal_daerah
            ]
        ]);
    }

    // Memproses Perbaikan Berkas (Resubmit) dari Kontingen
    public function resubmit(Request $request, $id)
    {
        $registration = EventRegistration::findOrFail($id);

        // Pastikan hanya akun kontingen pemilik yang bisa mengubah datanya
        if (!auth()->check() || auth()->id() !== $registration->user_id) {
            return abort(403);
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_ktp' => 'required|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Putra,Putri',
            'golongan_darah' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-,Tidak Tahu',
            'alamat' => 'required|string',
            'asal_daerah' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'bib_name' => 'required|string|max:15',
            'golongan_biaya' => 'required|string',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $usiaAktual = Carbon::parse($request->tanggal_lahir)->age;

        $event = $registration->event;
        $skemaBiaya = json_decode($event->skema_biaya, true);
        $nominalBayar = $registration->nominal_bayar;

        if ($skemaBiaya) {
            foreach ($skemaBiaya as $skema) {
                if ($skema['nama'] === $request->golongan_biaya) {
                    $nominalBayar = $skema['biaya'];
                    break;
                }
            }
        }

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_kejurnas_tf_rev_' . auth()->id() . '_' . rand(100,999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/pembayaran'), $filename);
            $registration->bukti_transfer = 'uploads/event/pembayaran/' . $filename;
        }

        $registration->nama_lengkap = $request->nama_lengkap;
        $registration->nomor_ktp = $request->nomor_ktp;
        $registration->tempat_lahir = $request->tempat_lahir;
        $registration->tanggal_lahir = $request->tanggal_lahir;
        $registration->usia = $usiaAktual;
        $registration->jenis_kelamin = $request->jenis_kelamin;
        $registration->golongan_darah = $request->golongan_darah;
        $registration->alamat = $request->alamat;
        $registration->asal_daerah = $request->asal_daerah;
        $registration->email = $request->email;
        $registration->nomor_telepon = $request->nomor_telepon;
        $registration->bib_name = strtoupper($request->bib_name);
        $registration->golongan_biaya = $request->golongan_biaya;
        $registration->nominal_bayar = $nominalBayar;

        // Reset status agar ditinjau ulang oleh admin
        $registration->status_pembayaran = 'Menunggu';
        $registration->pesan_penolakan = null;
        $registration->save();

        return redirect()->back()->with('success', 'Berkas pendaftaran atlet berhasil diperbaiki dan dikirim ulang untuk ditinjau.');
    }
}
