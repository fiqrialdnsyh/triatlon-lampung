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

        $allRegistrations = [];
        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            $allRegistrations = EventRegistration::where('event_id', $event->id)->latest()->get();
        }

        // AMBIL HISTORI DATA ATLET YANG SUDAH DIAJUKAN OLEH KONTINGEN TERKAIT (BISA BANYAK ATLET)
        $myAtletRegistrations = [];
        if (auth()->check() && auth()->user()->role === 'kontingen') {
            $myAtletRegistrations = EventRegistration::where('event_id', $event->id)
                                                    ->where('user_id', auth()->id())
                                                    ->latest()
                                                    ->get();
        }

        // REVISI: Mengubah dari event.open.show menjadi event.kejurnas.show
        return view('event.kejurnas.show', compact('event', 'kuotaTerisi', 'allRegistrations', 'myAtletRegistrations'));
    }

    public function register(Request $request, $id)
    {
        // VALIDASI AKSES: Hanya user dengan role 'kontingen' buatan admin yang boleh submit form
        if (!auth()->check() || auth()->user()->role !== 'kontingen') {
            return abort(403, 'Hanya akun kontingen resmi yang dapat mendaftarkan atlet pada Event Kejurnas.');
        }

        $event = Event::findOrFail($id);
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nomor_ktp' => 'required|numeric',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Putra,Putri',
            'golongan_darah' => 'required|in:A,B,AB,O,Tidak Tahu',
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
}
