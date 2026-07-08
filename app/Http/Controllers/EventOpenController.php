<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventOpenController extends Controller
{
    // Tampilan Katalog Utama Event Open
    public function index()
    {
        $events = Event::where('tipe', 'Open')->orderBy('tanggal_pelaksanaan', 'desc')->get();
        return view('event.open.index', compact('events'));
    }

    // Tampilan Form Tambah Event Baru (Admin)
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        return view('event.open.create');
    }

    // Proses Simpan Master Event Baru (Admin)
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'kuota_maksimal' => 'required|numeric|min:1',
            'batas_pendaftaran' => 'required|date_format:Y-m-d\TH:i',
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
            $skemaBiaya[] = [
                'nama' => $nama,
                'biaya' => (int) $request->biaya_golongan[$index]
            ];
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

    // Menampilkan Halaman Kelola (Edit Master Event)
    public function kelola()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $events = Event::where('tipe', 'Open')->orderBy('created_at', 'desc')->get();
        return view('event.open.kelola', compact('events'));
    }

    // Memproses Pembaruan Data Event
    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $event = Event::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'kuota_maksimal' => 'required|numeric|min:1',
            'batas_pendaftaran' => 'required|date_format:Y-m-d\TH:i',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thb_file' => 'nullable|mimes:pdf|max:10240',
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'atas_nama' => 'required|string',
            'link_wa_grup' => 'required|url',
            'status' => 'required|in:Buka,Tutup,Selesai',
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
            $filename = time() . '_poster_' . \Illuminate\Support\Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/poster'), $filename);
            $event->poster = 'uploads/event/poster/' . $filename;
        }

        if ($request->hasFile('thb_file')) {
            $file = $request->file('thb_file');
            $filename = time() . '_thb_' . \Illuminate\Support\Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/thb'), $filename);
            $event->thb_file = 'uploads/event/thb/' . $filename;
        }

        $event->judul = $request->judul;
        $event->slug = \Illuminate\Support\Str::slug($request->judul) . '-' . $event->id;
        $event->deskripsi = $request->deskripsi;
        $event->tanggal_pelaksanaan = $request->tanggal_pelaksanaan;
        $event->lokasi = $request->lokasi;
        $event->kuota_maksimal = $request->kuota_maksimal;
        $event->batas_pendaftaran = \Carbon\Carbon::parse($request->batas_pendaftaran);
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

    // Tampilan Detail Event & Ruang Verifikasi Admin
    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('tipe', 'Open')->firstOrFail();

        $kuotaTerisi = EventRegistration::where('event_id', $event->id)
            ->whereIn('status_pembayaran', ['Menunggu', 'Valid'])
            ->count();

        // Data pendaftar khusus untuk dibaca oleh Admin
        $allRegistrations = [];
        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            $allRegistrations = EventRegistration::where('event_id', $event->id)->latest()->get();
        }

        // Data pendaftaran milik user yang sedang login (Kandidat Peserta)
        $sudahDaftar = false;
        $pendaftaranUser = null;
        if (auth()->check()) {
            $pendaftaranUser = EventRegistration::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->latest() // Mengambil data yang paling baru diajukan
                ->first();

            // JIKA STATUSNYA DITOLAK, PESERTA DIANGGAP BELUM DAFTAR AGAR FORM BISA TERBUKA KEMBALI
            if ($pendaftaranUser && $pendaftaranUser->status_pembayaran !== 'Ditolak') {
                $sudahDaftar = true;
            }
        }

        return view('event.open.show', compact('event', 'kuotaTerisi', 'sudahDaftar', 'pendaftaranUser', 'allRegistrations'));
    }

    // Proses Pendaftaran Peserta Mandiri
    public function register(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $kuotaTerisi = EventRegistration::where('event_id', $event->id)->whereIn('status_pembayaran', ['Menunggu', 'Valid'])->count();

        if ($kuotaTerisi >= $event->kuota_maksimal || Carbon::now()->greaterThan(Carbon::parse($event->batas_pendaftaran))) {
            return back()->with('error', 'Pendaftaran gagal. Kuota penuh atau batas waktu registrasi telah berakhir.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'usia' => 'required|numeric|min:1',
            'jenis_kelamin' => 'required|in:Putra,Putri',
            'asal_daerah' => 'required|string|max:255',
            'kategori_lomba' => 'required|string|max:255',
            'golongan_biaya' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

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
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'asal_daerah' => $request->asal_daerah,
            'kategori_lomba' => $request->kategori_lomba,
            'golongan_biaya' => $request->golongan_biaya,
            'nominal_bayar' => $nominalBayar,
            'bukti_transfer' => $buktiPath,
            'qr_token' => 'FTI-' . strtoupper(Str::random(4)) . '-' . time(),
            'status_pembayaran' => 'Menunggu',
            'pesan_penolakan' => null // Reset kolom pesan penolakan pada entri baru
        ]);

        return redirect()->back()->with('success', 'Berkas pendaftaran baru Anda berhasil dikirim ke panitia.');
    }

    // PROSES VERIFIKASI PEMBAYARAN OLEH ADMIN (TERMASUK PESAN PENOLAKAN & RESET)
    public function verifikasi(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $registration = EventRegistration::findOrFail($id);

        // Tambahkan 'Menunggu' ke dalam validasi untuk opsi batalkan/reset
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

    // Menampilkan Dashboard Riwayat Pendaftaran Peserta
    public function history()
    {
        $registrations = EventRegistration::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->get();

        return view('event.open.history', compact('registrations'));
    }

    // Menampilkan Lembar Tiket Bukti QR Code Validasi Peserta
    public function showTiket($qr_token)
    {
        $ticket = EventRegistration::where('qr_token', $qr_token)
            ->where('status_pembayaran', 'Valid')
            ->with('event')
            ->firstOrFail();

        return view('event.open.tiket', compact('ticket'));
    }
}
