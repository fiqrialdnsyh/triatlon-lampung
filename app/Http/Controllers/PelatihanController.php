<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    // 1. Menampilkan daftar pelatihan
    public function index()
    {
        // Tambahkan with('pendaftarans') agar bisa menghitung jumlah pendaftar untuk logika kuota
        $pelatihans = Pelatihan::with('pendaftarans')->latest()->get();
        return view('pelatihan.index', compact('pelatihans'));
    }

    // 2. Menampilkan form tambah (Hanya Admin)
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return redirect('/pelatihan');
        }
        return view('pelatihan.create');
    }

    // 3. Menyimpan data ke database
    public function store(Request $request)
    {
        $biaya = [];
        if ($request->has('nama_golongan') && $request->has('biaya_golongan')) {
            foreach ($request->nama_golongan as $index => $nama) {
                if (!empty($nama)) {
                    $biaya[] = ['nama' => $nama, 'nominal' => $request->biaya_golongan[$index] ?? 0];
                }
            }
        }

        $konfigurasi_form = [
            'pekerjaan' => $request->has('form_pekerjaan'),
            'pengalaman' => $request->has('form_pengalaman'),
            'ukuran_baju' => $request->has('form_ukuran_baju'),
            'surat_rekomendasi' => $request->has('form_surat_rekomendasi'),
        ];

        Pelatihan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tempat' => $request->tempat, // Simpan Tempat
            'kuota' => $request->kuota, // Simpan Kuota
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'batas_pendaftaran' => $request->batas_pendaftaran,
            'status' => $request->status ?? 'Buka',
            'rekening' => $request->rekening,
            'biaya' => $biaya,
            'konfigurasi_form' => $konfigurasi_form,
        ]);

        return redirect('/pelatihan');
    }

    // 4. Menampilkan detail pelatihan (Admin vs Publik)
    public function show($id)
    {
        $pelatihan = Pelatihan::with('pendaftarans')->findOrFail($id);

        // Jika yang login adalah Admin, arahkan ke halaman kelola pendaftar
        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            return view('pelatihan.admin_show', compact('pelatihan'));
        }

        // Jika peserta biasa, arahkan ke halaman form pendaftaran
        return view('pelatihan.show', compact('pelatihan'));
    }

    // 5. Menampilkan form edit (Hanya Admin)
    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return redirect('/pelatihan');
        }
        $pelatihan = Pelatihan::findOrFail($id);
        return view('pelatihan.edit', compact('pelatihan'));
    }

    // 6. Memperbarui data pelatihan ke database
    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $biaya = [];
        if ($request->has('nama_golongan') && $request->has('biaya_golongan')) {
            foreach ($request->nama_golongan as $index => $nama) {
                if (!empty($nama)) {
                    $biaya[] = ['nama' => $nama, 'nominal' => $request->biaya_golongan[$index] ?? 0];
                }
            }
        }

        $konfigurasi_form = [
            'pekerjaan' => $request->has('form_pekerjaan'),
            'pengalaman' => $request->has('form_pengalaman'),
            'ukuran_baju' => $request->has('form_ukuran_baju'),
            'surat_rekomendasi' => $request->has('form_surat_rekomendasi'),
        ];

        $pelatihan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tempat' => $request->tempat, // Update Tempat
            'kuota' => $request->kuota, // Update Kuota
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'batas_pendaftaran' => $request->batas_pendaftaran,
            'status' => $request->status ?? 'Buka',
            'rekening' => $request->rekening,
            'biaya' => $biaya,
            'konfigurasi_form' => $konfigurasi_form,
        ]);

        return redirect('/pelatihan')->with('success', 'Pelatihan berhasil diperbarui!');
    }
}
