<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventOpenController extends Controller
{
    // Menampilkan Katalog Utama Event Open
    public function index()
    {
        $events = Event::where('tipe', 'Open')->orderBy('tanggal_pelaksanaan', 'desc')->get();
        return view('event.open.index', compact('events'));
    }

    // Menampilkan Form Tambah Event Baru (Khusus Admin)
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        return view('event.open.create');
    }

    // Memproses Simpan Master Event Baru
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
        ], [
            'tanggal_pelaksanaan.after_or_equal' => 'Tanggal pelaksanaan tidak boleh menggunakan tanggal yang sudah lewat.',
            'batas_pendaftaran.before_or_equal' => 'Batas waktu pendaftaran tidak boleh melebihi tanggal pelaksanaan event.'
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

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_poster_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/poster'), $filename);
            $posterPath = 'uploads/event/poster/' . $filename;
        }

        $thbPath = null;
        if ($request->hasFile('thb_file')) {
            $file = $request->file('thb_file');
            $filename = time() . '_thb_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/thb'), $filename);
            $thbPath = 'uploads/event/thb/' . $filename;
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
            'poster' => $posterPath,
            'thb_file' => $thbPath,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'link_wa_grup' => $request->link_wa_grup,
            'skema_biaya' => json_encode($skemaBiaya),
            'tipe' => 'Open',
            'status' => 'Buka'
        ]);

        return redirect()->route('event.open.index')->with('success', 'Event Open Tournament berhasil dipublikasikan');
    }

    // Menampilkan Detail Event & Ruang Verifikasi (Admin) atau Form Pendaftaran (Peserta)
    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('tipe', 'Open')->firstOrFail();

        $kuotaTerisi = EventRegistration::where('event_id', $event->id)
            ->whereIn('status_pembayaran', ['Menunggu', 'Valid'])
            ->count();

        $allRegistrations = collect();
        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            $allRegistrations = EventRegistration::where('event_id', $event->id)->latest()->get();
        }

        $sudahDaftar = false;
        $pendaftaranUser = null;
        if (auth()->check()) {
            $pendaftaranUser = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->latest()
                ->first();

            if ($pendaftaranUser && $pendaftaranUser->status_pembayaran !== 'Ditolak') {
                $sudahDaftar = true;
            }
        }

        return view('event.open.show', compact('event', 'kuotaTerisi', 'sudahDaftar', 'pendaftaranUser', 'allRegistrations'));
    }

    // Memproses Pengiriman Berkas Pendaftaran Peserta
    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $kuotaTerisi = EventRegistration::where('event_id', $event->id)->whereIn('status_pembayaran', ['Menunggu', 'Valid'])->count();

        if ($kuotaTerisi >= $event->kuota_maksimal || Carbon::now()->greaterThan(Carbon::parse($event->batas_pendaftaran))) {
            return back()->with('error', 'Pendaftaran gagal. Kuota penuh atau batas waktu registrasi telah berakhir.');
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
            $filename = time() . '_tf_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/pembayaran'), $filename);
            $buktiPath = 'uploads/event/pembayaran/' . $filename;
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
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
            'qr_token' => 'FTI-' . strtoupper(Str::random(4)) . '-' . time(),
            'status_pembayaran' => 'Menunggu',
            'pesan_penolakan' => null
        ]);

        return redirect()->back()->with('success', 'Berkas pendaftaran baru Anda berhasil dikirim ke panitia.');
    }

    // Memproses Verifikasi Status Pembayaran & Reset (Oleh Admin)
    public function verifikasi(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $registration = EventRegistration::findOrFail($id);

        $request->validate([
            'status_pembayaran' => 'required|in:Valid,Ditolak,Menunggu',
            'pesan_penolakan' => 'nullable|string|max:500'
        ]);

        $registration->status_pembayaran = $request->status_pembayaran;

        if ($request->status_pembayaran === 'Ditolak') {
            $registration->pesan_penolakan = $request->pesan_penolakan ?? 'Bukti transaksi tidak valid atau tidak sesuai nominal.';
        } else {
            $registration->pesan_penolakan = null;
        }

        $registration->save();
        return redirect()->back()->with('success', 'Status validasi peserta berhasil diperbarui.');
    }

    // Menampilkan Dashboard Kelola Master Event
    public function kelola()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $events = Event::where('tipe', 'Open')->orderBy('created_at', 'desc')->get();
        return view('event.open.kelola', compact('events'));
    }

    // Memproses Pembaruan Modifikasi Master Event
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
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thb_file' => 'nullable|mimes:pdf|max:10240',
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'atas_nama' => 'required|string',
            'link_wa_grup' => 'required|url',
            'status' => 'required|in:Buka,Tutup,Selesai',
        ], [
            'tanggal_pelaksanaan.after_or_equal' => 'Tanggal pelaksanaan tidak boleh menggunakan tanggal yang sudah lewat.',
            'batas_pendaftaran.before_or_equal' => 'Batas waktu pendaftaran tidak boleh melebihi tanggal pelaksanaan event.'
        ]);

        if ($request->has('nama_golongan') && $request->has('biaya_golongan')) {
            $skemaBiaya = [];
            foreach ($request->nama_golongan as $index => $nama) {
                if (!empty($nama)) {
                    $skemaBiaya[] = [
                        'nama' => $nama,
                        'biaya' => (int) $request->biaya_golongan[$index]
                    ];
                }
            }
            $event->skema_biaya = json_encode($skemaBiaya);
        }

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_poster_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/poster'), $filename);
            $event->poster = 'uploads/event/poster/' . $filename;
        }

        if ($request->hasFile('thb_file')) {
            $file = $request->file('thb_file');
            $filename = time() . '_thb_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/thb'), $filename);
            $event->thb_file = 'uploads/event/thb/' . $filename;
        }

        $event->judul = $request->judul;
        $event->slug = Str::slug($request->judul) . '-' . $event->id;
        $event->deskripsi = $request->deskripsi;
        $event->tanggal_pelaksanaan = $request->tanggal_pelaksanaan;
        $event->lokasi = $request->lokasi;
        $event->kategori_lomba = $request->kategori_lomba;
        $event->kuota_maksimal = $request->kuota_maksimal;
        $event->batas_pendaftaran = Carbon::parse($request->batas_pendaftaran);
        $event->nama_bank = $request->nama_bank;
        $event->nomor_rekening = $request->nomor_rekening;
        $event->atas_nama = $request->atas_nama;
        $event->link_wa_grup = $request->link_wa_grup;
        $event->status = $request->status;
        $event->save();

        return redirect()->route('event.open.kelola')->with('success', 'Perubahan pada master event berhasil disimpan.');
    }

    // Memproses Penghapusan Event
    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        Event::findOrFail($id)->delete();
        return redirect()->route('event.open.kelola')->with('success', 'Event telah ditarik dan dihapus dari sistem.');
    }

    // Menampilkan Dashboard Riwayat & QR Code Tiket
    public function history()
    {
        $registrations = EventRegistration::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->get();

        return view('event.open.history', compact('registrations'));
    }

    // Memproses Data dari Scanner QR Code Kamera Admin dengan Validasi Nomor Pertandingan Spasial
    public function checkIn(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.']);
        }

        $token = $request->qr_token;
        $eventId = $request->event_id; // Menerima parameter ID Event dari halaman aktif

        // Langkah 1: Cari pendaftaran yang COCOK dengan Token QR sekaligus ID Event ini
        $registration = EventRegistration::where('qr_token', $token)
                                          ->where('event_id', $eventId)
                                          ->first();

        if (!$registration) {
            // Langkah 2: Jika tidak cocok, cek apakah token ini sebenarnya terdaftar di nomor pertandingan/event lain
            $crossRegistration = EventRegistration::where('qr_token', $token)->with('event')->first();

            if ($crossRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'SALAH MEJA / NOMOR PERTANDINGAN! Atlet ini terdaftar pada event: "' . $crossRegistration->event->judul . '". Silakan arahkan atlet ke meja scanner nomor pertandingan yang sesuai.'
                ]);
            }

            return response()->json(['success' => false, 'message' => 'QR Code tidak terekam dalam sistem kejuaraan kami.']);
        }

        // Langkah 3: Validasi status kelulusan berkas/pembayaran keuangan
        if ($registration->status_pembayaran !== 'Valid') {
            return response()->json(['success' => false, 'message' => 'Pendaftaran atlet ini belum disetujui/ditolak oleh bendahara pelaksana.']);
        }

        // Langkah 4: Validasi duplikasi absensi kedatangan lapangan
        if ($registration->waktu_checkin !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Atlet sudah melakukan absensi kedatangan sebelumnya pada jam: ' . \Carbon\Carbon::parse($registration->waktu_checkin)->format('d/m/Y H:i:s')
            ]);
        }

        // Catat waktu kehadiran mutlak
        $registration->waktu_checkin = Carbon::now();
        $registration->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Check-In!',
            'data' => [
                'nama' => $registration->nama_lengkap,
                'bib' => $registration->bib_name,
                'kategori' => $registration->kategori_lomba,
                'asal' => $registration->asal_daerah
            ]
        ]);
    }

    // Menampilkan Halaman Khusus Cetak Laporan Check-In (PDF)
    public function printCheckIn($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $event = Event::findOrFail($id);

        // Hanya mengambil pendaftar yang kolom waktu_checkin-nya tidak kosong (sudah absen)
        $checkins = EventRegistration::where('event_id', $event->id)
                        ->whereNotNull('waktu_checkin')
                        ->orderBy('waktu_checkin', 'asc')
                        ->get();

        return view('event.open.print-checkin', compact('event', 'checkins'));
    }
}
