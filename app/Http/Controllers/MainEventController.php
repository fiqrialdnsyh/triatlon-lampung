<?php

namespace App\Http\Controllers;

use App\Models\MainEvent;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MainEventController extends Controller
{
    // 1. HALAMAN KATALOG PESERTA (GABUNGAN)
    public function index()
    {
        // Menampilkan semua event besar yang aktif
        $mainEvents = MainEvent::latest()->get();
        return view('main_event.index', compact('mainEvents'));
    }

    // 2. HALAMAN DETAIL PESERTA DENGAN TAB TOGGLE
    public function show($slug)
    {
        $mainEvent = MainEvent::where('slug', $slug)->with('subEvents')->firstOrFail();

        $eventOpen = $mainEvent->subEvents->where('tipe', 'Open')->first();
        $eventKejurnas = $mainEvent->subEvents->where('tipe', 'Kejurnas')->first();

        $sudahDaftarOpen = false;
        $sudahDaftarKejurnas = false;
        $myAtletRegistrations = collect();

        if (auth()->check()) {
            if ($eventOpen) {
                $sudahDaftarOpen = \App\Models\EventRegistration::where('event_id', $eventOpen->id)->where('user_id', auth()->id())->where('status_pembayaran', '!=', 'Ditolak')->exists();
            }
            if ($eventKejurnas) {
                $sudahDaftarKejurnas = \App\Models\EventRegistration::where('event_id', $eventKejurnas->id)->where('user_id', auth()->id())->exists();

                // Ambil semua atlet yang sudah didaftarkan kontingen ini untuk event Kejurnas ini
                if (auth()->user()->role === 'kontingen') {
                    $myAtletRegistrations = \App\Models\EventRegistration::where('event_id', $eventKejurnas->id)
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get();
                }
            }
        }

        return view('main_event.show', compact('mainEvent', 'eventOpen', 'eventKejurnas', 'sudahDaftarOpen', 'sudahDaftarKejurnas', 'myAtletRegistrations'));
    }

    // 3. HALAMAN TAMBAH EVENT KHUSUS ADMIN
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        return view('main_event.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:today',
            'lokasi' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            // Validasi Jalur (Mencegah error buka_jalur is required)
            'buka_jalur' => 'required|array|min:1',

            // Validasi Array Nomor Perlombaan
            'kategori_nama' => 'required|array|min:1',
            'kategori_target' => 'required|array|min:1',

            'kuota_maksimal' => 'required|numeric|min:1',
            'batas_pendaftaran' => 'required|date|before_or_equal:tanggal_pelaksanaan',
            'nama_bank' => 'required|string',
            'nomor_rekening' => 'required|string',
            'atas_nama' => 'required|string',
            'link_wa_grup' => 'required|url',
            'nama_golongan' => 'required|array|min:1',
            'biaya_golongan' => 'required|array|min:1',
        ], [
            'buka_jalur.required' => 'Anda wajib mencentang minimal satu jalur pendaftaran (Open / Kejurnas).'
        ]);

        $isOpen = in_array('Open', $request->buka_jalur);
        $isKejurnas = in_array('Kejurnas', $request->buka_jalur);

        // Memilah Nomor Perlombaan Berdasarkan Target Jalur
        $katOpen = [];
        $katKejurnas = [];

        foreach ($request->kategori_nama as $index => $nama) {
            if (!empty($nama)) {
                $target = $request->kategori_target[$index];
                if ($target === 'Semua' || $target === 'Open') $katOpen[] = $nama;
                if ($target === 'Semua' || $target === 'Kejurnas') $katKejurnas[] = $nama;
            }
        }

        // Cek pengamanan ganda
        if ($isOpen && count($katOpen) == 0) {
            return back()->withErrors(['kategori' => 'Jalur Open diaktifkan, tapi tidak ada nomor perlombaan yang Anda alokasikan untuk Open.'])->withInput();
        }
        if ($isKejurnas && count($katKejurnas) == 0) {
            return back()->withErrors(['kategori' => 'Jalur Kejurnas diaktifkan, tapi tidak ada nomor perlombaan yang Anda alokasikan untuk Kejurnas.'])->withInput();
        }

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_mainposter_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/poster'), $filename);
            $posterPath = 'uploads/event/poster/' . $filename;
        }

        $mainEvent = MainEvent::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . time(),
            'deskripsi' => $request->deskripsi,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'lokasi' => $request->lokasi,
            'poster' => $posterPath,
            'is_open_active' => $isOpen,
            'is_kejurnas_active' => $isKejurnas,
        ]);

        $skemaBiaya = [];
        foreach ($request->nama_golongan as $index => $nama) {
            if (!empty($nama)) {
                $skemaBiaya[] = ['nama' => $nama, 'biaya' => (int) $request->biaya_golongan[$index]];
            }
        }
        $jsonBiaya = json_encode($skemaBiaya);

        $thbPath = null;
        if ($request->hasFile('thb_file')) {
            $file = $request->file('thb_file');
            $filename = time() . '_thb_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/event/thb'), $filename);
            $thbPath = 'uploads/event/thb/' . $filename;
        }

        // Data Bersama (Tanpa input kategori_lomba yang sudah usang)
        $dataBersama = [
            'main_event_id' => $mainEvent->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'lokasi' => $request->lokasi,
            'kuota_maksimal' => $request->kuota_maksimal,
            'batas_pendaftaran' => \Carbon\Carbon::parse($request->batas_pendaftaran),
            'poster' => $posterPath,
            'thb_file' => $thbPath,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'link_wa_grup' => $request->link_wa_grup,
            'skema_biaya' => $jsonBiaya,
            'status' => 'Buka'
        ];

        if ($isOpen) {
            $dataOpen = $dataBersama;
            $dataOpen['slug'] = Str::slug($request->judul) . '-open-' . time();
            $dataOpen['tipe'] = 'Open';
            $dataOpen['kategori_lomba'] = implode(', ', $katOpen);
            Event::create($dataOpen);
        }

        if ($isKejurnas) {
            $dataKejurnas = $dataBersama;
            $dataKejurnas['slug'] = Str::slug($request->judul) . '-kejurnas-' . time();
            $dataKejurnas['tipe'] = 'Kejurnas';
            $dataKejurnas['kategori_lomba'] = implode(', ', $katKejurnas);
            Event::create($dataKejurnas);
        }

        return redirect()->route('main_event.index')->with('success', 'Master Event berhasil dibuat dan disinkronkan.');
    }
}
