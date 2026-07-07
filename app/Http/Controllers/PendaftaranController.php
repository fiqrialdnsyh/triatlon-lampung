<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function store(Request $request, $pelatihanId)
    {
        // Validasi input
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

        // Proses upload bukti pembayaran
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_bayar'), $filename);
            $buktiPath = 'uploads/bukti_bayar/' . $filename;
        }

        // Proses upload surat rekomendasi (opsional)
        $suratPath = null;
        if ($request->hasFile('surat_rekomendasi')) {
            $file = $request->file('surat_rekomendasi');
            $filename = time() . '_rekomendasi_' . auth()->id() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/surat_rekomendasi'), $filename);
            $suratPath = 'uploads/surat_rekomendasi/' . $filename;
        }

        // Cek apakah user sudah pernah mendaftar di pelatihan ini
        $pendaftaranLama = Pendaftaran::where('user_id', auth()->id())
                                      ->where('pelatihan_id', $pelatihanId)
                                      ->first();

        if ($pendaftaranLama) {
            if ($pendaftaranLama->status === 'Ditolak') {
                // Jika sebelumnya ditolak, timpa dengan data baru untuk submit ulang
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
                    'status' => 'Menunggu', // Reset status menjadi menunggu kembali
                    'alasan_ditolak' => null
                ]);
                return redirect('/pelatihan/' . $pelatihanId)->with('success', 'Pendaftaran berhasil dikirim ulang.');
            }
            return redirect('/pelatihan/' . $pelatihanId)->with('error', 'Anda sudah mendaftar pada pelatihan ini.');
        }

        // Simpan pendaftaran baru
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
        ]);

        return redirect('/pelatihan/' . $pelatihanId)->with('success', 'Pendaftaran berhasil dikirim.');
    }

    // Fungsi untuk menerima pendaftaran
    public function terima($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update(['status' => 'Diterima', 'alasan_ditolak' => null]);

        return back()->with('success', 'Pendaftaran atas nama ' . $pendaftaran->nama_lengkap . ' berhasil DITERIMA.');
    }

    // Fungsi untuk menolak pendaftaran
    public function tolak(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'status' => 'Ditolak',
            'alasan_ditolak' => $request->alasan_ditolak
        ]);

        return back()->with('success', 'Pendaftaran atas nama ' . $pendaftaran->nama_lengkap . ' berhasil DITOLAK.');
    }

    // Menampilkan tiket pendaftaran untuk peserta
    public function cetakTiket($pelatihanId)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Mengambil data pendaftaran user yang berstatus 'Diterima' pada pelatihan terkait
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())
                                  ->where('pelatihan_id', $pelatihanId)
                                  ->where('status', 'Diterima')
                                  ->firstOrFail();

        return view('pelatihan.tiket', compact('pendaftaran'));
    }
}
