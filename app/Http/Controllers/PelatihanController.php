<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PelatihanController extends Controller
{
    private function autoUpdateStatusSelesai($pelatihan)
    {
        $H_plus_satu = Carbon::parse($pelatihan->tanggal_pelaksanaan)->addDay()->endOfDay();
        if (Carbon::now()->greaterThan($H_plus_satu) && $pelatihan->status !== 'Selesai') {
            $pelatihan->status = 'Selesai';
            $pelatihan->save();
        }
    }

    public function index()
    {
        $pelatihans = Pelatihan::latest()->get();

        foreach ($pelatihans as $pelatihan) {
            $this->autoUpdateStatusSelesai($pelatihan);
        }

        return view('pelatihan.index', compact('pelatihans'));
    }

    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return abort(403);
        }
        return view('pelatihan.create');
    }

    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return abort(403);
        }

        $pelatihan = Pelatihan::findOrFail($id);
        return view('pelatihan.edit', compact('pelatihan'));
    }

    // 4. Memproses Pembaruan Data Edit Pelatihan
    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return abort(403);
        }

        $pelatihan = Pelatihan::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date',
            'batas_pendaftaran' => 'required|date|before:tanggal_pelaksanaan',
            'kuota_maksimal' => 'required|numeric|min:1',
            'lokasi' => 'required|string|max:255',
            'rekening' => 'required|string|max:255',
            'link_wa_grup' => 'required|url',
            'status' => 'required|in:Buka,Tutup,Selesai'
        ]);

        $pelatihan->judul = $request->judul;
        $pelatihan->deskripsi = $request->deskripsi;
        $pelatihan->tanggal_pelaksanaan = $request->tanggal_pelaksanaan;
        $pelatihan->batas_pendaftaran = \Carbon\Carbon::parse($request->batas_pendaftaran);
        $pelatihan->kuota_maksimal = $request->kuota_maksimal;
        $pelatihan->lokasi = $request->lokasi;
        $pelatihan->rekening = $request->rekening;
        $pelatihan->link_wa_grup = $request->link_wa_grup;
        $pelatihan->status = $request->status;

        // Memproses ulang biaya jika ada perubahan
        if ($request->has('nama_golongan') && $request->has('biaya_golongan')) {
            $biayaArray = [];
            foreach ($request->nama_golongan as $index => $nama) {
                if (!empty($nama) && isset($request->biaya_golongan[$index])) {
                    $biayaArray[] = [
                        'nama' => $nama,
                        'nominal' => $request->biaya_golongan[$index]
                    ];
                }
            }
            // Karena model Pelatihan mungkin belum men-cast otomatis, kita gunakan array
            $pelatihan->biaya = count($biayaArray) > 0 ? $biayaArray : null;
        }

        $pelatihan->save();

        return redirect('/pelatihan')->with('success', 'Data Pelatihan berhasil diperbarui.');
    }

    public function show($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $this->autoUpdateStatusSelesai($pelatihan);

        $kuotaTerisi = Pendaftaran::where('pelatihan_id', $pelatihan->id)
            ->whereIn('status', ['Menunggu', 'Diterima'])
            ->count();

        $allRegistrations = [];
        $checkedInList = [];

        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            $allRegistrations = Pendaftaran::where('pelatihan_id', $pelatihan->id)->latest()->get();

            $checkedInList = Pendaftaran::where('pelatihan_id', $pelatihan->id)
                ->whereNotNull('waktu_checkin')
                ->orderBy('waktu_checkin', 'desc')
                ->get();
        }

        return view('pelatihan.show', compact('pelatihan', 'kuotaTerisi', 'allRegistrations', 'checkedInList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_pelaksanaan' => 'required|date|after_or_equal:today',
            'batas_pendaftaran' => 'required|date|before:tanggal_pelaksanaan',
            'kuota_maksimal' => 'required|numeric|min:1',
            'lokasi' => 'required|string|max:255',
            'rekening' => 'required|string|max:255',
            'link_wa_grup' => 'required|url',
        ]);

        $pelatihan = new Pelatihan();
        $pelatihan->judul = $request->judul;
        $pelatihan->deskripsi = $request->deskripsi;
        $pelatihan->tanggal_pelaksanaan = $request->tanggal_pelaksanaan;
        $pelatihan->batas_pendaftaran = Carbon::parse($request->batas_pendaftaran);
        $pelatihan->kuota_maksimal = $request->kuota_maksimal;
        $pelatihan->lokasi = $request->lokasi;
        $pelatihan->rekening = $request->rekening;
        $pelatihan->link_wa_grup = $request->link_wa_grup;
        $pelatihan->status = 'Buka';

        if ($request->has('nama_golongan') && $request->has('biaya_golongan')) {
            $biayaArray = [];
            foreach ($request->nama_golongan as $index => $nama) {
                if (!empty($nama) && isset($request->biaya_golongan[$index])) {
                    $biayaArray[] = [
                        'nama' => $nama,
                        'nominal' => $request->biaya_golongan[$index],
                    ];
                }
            }
            $pelatihan->biaya = count($biayaArray) > 0 ? $biayaArray : null;
        }

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_pelatihan.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pelatihan/poster'), $filename);
            $pelatihan->poster = 'uploads/pelatihan/poster/' . $filename;
        }

        $pelatihan->save();

        return redirect('/pelatihan')->with('success', 'Master Pelatihan berhasil dipublikasikan!');
    }

    // Check-in peserta via scan QR tiket (pakai model Pendaftaran, bukan PelatihanRegistration)
    public function checkIn(Request $request)
    {
        $pendaftaran = Pendaftaran::where('qr_token', $request->qr_token)
            ->where('pelatihan_id', $request->pelatihan_id)
            ->first();

        if (!$pendaftaran) {
            return response()->json(['success' => false, 'message' => 'QR Code tidak terdaftar pada pelatihan ini.']);
        }
        if ($pendaftaran->status !== 'Diterima') {
            return response()->json(['success' => false, 'message' => 'Peserta belum diverifikasi/diterima.']);
        }
        if ($pendaftaran->waktu_checkin !== null) {
            return response()->json(['success' => false, 'message' => 'Peserta sudah absen sebelumnya.']);
        }

        $pendaftaran->waktu_checkin = Carbon::now();
        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $pendaftaran->nama_lengkap,
                'kategori' => 'Peserta Pelatihan',
            ],
        ]);
    }

    // Menampilkan Halaman Riwayat Pelatihan Peserta
    public function history()
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Mengambil data pendaftaran milik user yang sedang login beserta relasi pelatihannya
        $registrations = \App\Models\Pendaftaran::where('user_id', auth()->id())
            ->with('pelatihan')
            ->latest()
            ->get();

        return view('pelatihan.history', compact('registrations'));
    }

    public function printCheckIn($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $checkins = Pendaftaran::where('pelatihan_id', $pelatihan->id)
            ->whereNotNull('waktu_checkin')
            ->orderBy('waktu_checkin', 'asc')
            ->get();

        return view('pelatihan.print-checkin', compact('pelatihan', 'checkins'));
    }
}
