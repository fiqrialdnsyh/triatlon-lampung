<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\AdminNewRegistrationMail;
use App\Mail\RegistrationStatusMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    public function store(Request $request, $pelatihanId)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'usia' => 'required|integer',
            'pengalaman_melatih' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'asal_daerah' => 'required|string|max:255',
            'ukuran_baju' => 'required|string',
            'golongan_biaya' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'surat_rekomendasi' => 'nullable|mimes:pdf|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti_bayar', $filename, 'private');
            $buktiPath = 'bukti_bayar/' . $filename;
        }

        $suratPath = null;
        if ($request->hasFile('surat_rekomendasi')) {
            $file = $request->file('surat_rekomendasi');
            $filename = time() . '_rekomendasi_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_rekomendasi', $filename, 'private');
            $suratPath = 'surat_rekomendasi/' . $filename;
        }

        $pendaftaranLama = Pendaftaran::where('user_id', auth()->id())
            ->where('pelatihan_id', $pelatihanId)
            ->first();

        if ($pendaftaranLama) {
            if ($pendaftaranLama->status === 'Ditolak') {
                $pendaftaranLama->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'usia' => $request->usia,
                    'pengalaman_melatih' => $request->pengalaman_melatih,
                    'pengalaman_lainnya' => $request->pengalaman_lainnya,
                    'pekerjaan' => $request->pekerjaan,
                    'asal_daerah' => $request->asal_daerah,
                    'ukuran_baju' => $request->ukuran_baju,
                    'golongan_biaya' => $request->golongan_biaya,
                    'bukti_pembayaran' => $buktiPath,
                    'surat_rekomendasi' => $suratPath,
                    'status' => 'Menunggu',
                    'alasan_ditolak' => null,
                    // Penambahan pengecekan QR Token di sini
                    'qr_token' => $pendaftaranLama->qr_token ?? 'PLT-' . strtoupper(Str::random(6)) . '-' . time(),
                ]);
                return redirect('/pelatihan/' . $pelatihanId)->with('success', 'Pendaftaran berhasil dikirim ulang.');
            }

            // Kirim notifikasi ke semua admin
            $pelatihan = \App\Models\Pelatihan::find($pelatihanId);
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();

            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminNewRegistrationMail([
                    'jenis' => 'Pelatihan',
                    'nama_kegiatan' => $pelatihan->judul,
                    'nama_pendaftar' => $request->nama_lengkap,
                    'detail' => [
                        'Usia' => $request->usia . ' Tahun',
                        'Jenis Kelamin' => $request->jenis_kelamin,
                        'Pekerjaan' => $request->pekerjaan,
                        'Asal Daerah' => $request->asal_daerah,
                        'Golongan Biaya' => $request->golongan_biaya,
                    ],
                    'link_verifikasi' => url('/pelatihan/' . $pelatihanId),
                    'waktu_daftar' => now()->translatedFormat('d F Y, H:i') . ' WIB',
                ]));
            }

            return redirect('/pelatihan/' . $pelatihanId)->with('error', 'Anda sudah mendaftar pada pelatihan ini.');
        }

        Pendaftaran::create([
            'user_id' => auth()->id(),
            'pelatihan_id' => $pelatihanId,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'pengalaman_melatih' => $request->pengalaman_melatih,
            'pengalaman_lainnya' => $request->pengalaman_lainnya,
            'pekerjaan' => $request->pekerjaan,
            'asal_daerah' => $request->asal_daerah,
            'ukuran_baju' => $request->ukuran_baju,
            'golongan_biaya' => $request->golongan_biaya,
            'bukti_pembayaran' => $buktiPath,
            'surat_rekomendasi' => $suratPath,
            'status' => 'Menunggu',
            'qr_token' => 'PLT-' . strtoupper(Str::random(10)) . '-' . strtoupper(Str::random(6)),
        ]);

        return redirect('/pelatihan/' . $pelatihanId)->with('success', 'Pendaftaran berhasil dikirim.');
    }

    // Kirim ulang berkas setelah ditolak (dipanggil dari route pendaftaran.resubmit)
    public function resubmit(Request $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        if ($pendaftaran->user_id !== auth()->id()) return abort(403);
        if ($pendaftaran->status !== 'Ditolak') {
            return redirect()->back()->with('error', 'Pendaftaran ini tidak berstatus ditolak.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'usia' => 'required|integer',
            'pengalaman_melatih' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'asal_daerah' => 'required|string|max:255',
            'ukuran_baju' => 'required|string',
            'golongan_biaya' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'surat_rekomendasi' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'pengalaman_melatih' => $request->pengalaman_melatih,
            'pengalaman_lainnya' => $request->pengalaman_lainnya,
            'pekerjaan' => $request->pekerjaan,
            'asal_daerah' => $request->asal_daerah,
            'ukuran_baju' => $request->ukuran_baju,
            'golongan_biaya' => $request->golongan_biaya,
            'status' => 'Menunggu',
            'alasan_ditolak' => null,
            // Penambahan pengecekan QR Token di sini juga
            'qr_token' => $pendaftaran->qr_token ?? 'PLT-' . strtoupper(Str::random(6)) . '-' . time(),
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti_bayar', $filename, 'private');
            $data['bukti_pembayaran'] = 'uploads/bukti_bayar/' . $filename;
        }

        if ($request->hasFile('surat_rekomendasi')) {
            $file = $request->file('surat_rekomendasi');
            $filename = time() . '_rekomendasi_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('surat_rekomendasi', $filename, 'private');
            $data['surat_rekomendasi'] = 'uploads/surat_rekomendasi/' . $filename;
        }

        $pendaftaran->update($data);

        return redirect('/pelatihan/' . $pendaftaran->pelatihan_id)->with('success', 'Berkas berhasil dikirim ulang.');
    }

    public function terima($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => 'Diterima', 'alasan_ditolak' => null]);

        Mail::to($pendaftaran->user->email)->send(new RegistrationStatusMail([
            'status' => 'Diterima',
            'jenis' => 'Pelatihan',
            'nama_kegiatan' => $pendaftaran->pelatihan->judul,
            'nama_pendaftar' => $pendaftaran->nama_lengkap,
            'detail' => [
                'Tanggal Pelaksanaan' => \Carbon\Carbon::parse($pendaftaran->pelatihan->tanggal_pelaksanaan)->translatedFormat('d F Y'),
                'Lokasi' => $pendaftaran->pelatihan->lokasi,
            ],
            'link_terkait' => route('pendaftaran.tiket', $pendaftaran->pelatihan_id),
            'link_label' => 'Cetak Tiket QR',
        ]));

        return back()->with('success', 'Pendaftaran atas nama ' . $pendaftaran->nama_lengkap . ' berhasil DITERIMA.');
    }

    public function tolak(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'Ditolak',
            'alasan_ditolak' => $request->pesan_penolakan ?? $request->alasan_ditolak,
        ]);

        Mail::to($pendaftaran->user->email)->send(new RegistrationStatusMail([
            'status' => 'Ditolak',
            'jenis' => 'Pelatihan',
            'nama_kegiatan' => $pendaftaran->pelatihan->judul,
            'nama_pendaftar' => $pendaftaran->nama_lengkap,
            'alasan_penolakan' => $pendaftaran->alasan_ditolak,
            'link_terkait' => url('/pelatihan/' . $pendaftaran->pelatihan_id),
            'link_label' => 'Perbaiki & Kirim Ulang',
        ]));

        return back()->with('success', 'Pendaftaran atas nama ' . $pendaftaran->nama_lengkap . ' berhasil DITOLAK.');
    }

    // Reset status pendaftaran kembali ke Menunggu (tombol "Batalkan" di panel admin)
    public function batal($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'Menunggu',
            'alasan_ditolak' => null,
            'waktu_checkin' => null,
        ]);

        return back()->with('success', 'Status pendaftaran atas nama ' . $pendaftaran->nama_lengkap . ' direset ke Menunggu.');
    }

    public function cetakTiket($pelatihanId)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $pendaftaran = Pendaftaran::where('user_id', auth()->id())
            ->where('pelatihan_id', $pelatihanId)
            ->where('status', 'Diterima')
            ->firstOrFail();

        return view('pelatihan.tiket', compact('pendaftaran'));
    }
}
