<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengurusController extends Controller
{
    // Menampilkan halaman susunan pengurus untuk publik
    public function index()
    {
        // Memecah data berdasarkan kategorinya agar mudah diatur di tampilan (Hierarki)
        $dewan = Pengurus::where('kategori', 'Dewan')->get();
        $inti = Pengurus::where('kategori', 'Inti')->get();
        $bidang = Pengurus::where('kategori', 'Bidang')->get();
        $cabang = Pengurus::where('kategori', 'Cabang')->get();

        return view('pengurus.index', compact('dewan', 'inti', 'bidang', 'cabang'));
    }

    // Menampilkan halaman form tambah pengurus
    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/pengurus');
        }

        // 1. Ambil data pengurus provinsi yang sudah ada untuk ditampilkan sebagai informasi
        $dewanExist = \App\Models\Pengurus::where('kategori', 'Dewan')->get();
        $intiExist = \App\Models\Pengurus::where('kategori', 'Inti')->get();
        $bidangExist = \App\Models\Pengurus::where('kategori', 'Bidang')->get();

        // 2. Logika Cerdas Cabang: Hanya tampilkan daerah yang BELUM diinput
        $daerahTerisi = \App\Models\Pengurus::where('kategori', 'Cabang')
            ->pluck('nama_daerah')
            ->map(function($item) {
                return strtolower(trim($item));
            })->toArray();

        // Daftar 15 Kabupaten/Kota di Provinsi Lampung
        $semuaDaerah = [
            'Bandar Lampung', 'Metro', 'Pesawaran', 'Pringsewu', 'Tanggamus',
            'Lampung Selatan', 'Lampung Tengah', 'Lampung Utara', 'Lampung Barat', 'Lampung Timur',
            'Way Kanan', 'Tulang Bawang', 'Tulang Bawang Barat', 'Mesuji', 'Pesisir Barat'
        ];

        // Saring daerah: Hapus dari daftar jika sudah ada di database
        $daerahTersedia = array_filter($semuaDaerah, function($d) use ($daerahTerisi) {
            return !in_array(strtolower($d), $daerahTerisi);
        });

        return view('pengurus.create', compact('dewanExist', 'intiExist', 'bidangExist', 'daerahTersedia'));
    }

    /// Menampilkan halaman Kelola dengan gaya per-sesi (seperti Create)
    public function kelola()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/pengurus');
        }

        $dewan = Pengurus::where('kategori', 'Dewan')->get();
        $inti = Pengurus::where('kategori', 'Inti')->get();
        $bidang = Pengurus::where('kategori', 'Bidang')->get();
        $cabang = Pengurus::where('kategori', 'Cabang')->orderBy('nama_daerah')->get();

        return view('pengurus.kelola', compact('dewan', 'inti', 'bidang', 'cabang'));
    }



    // Menampilkan halaman form edit pengurus individual
    public function edit($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);
        $pengurus = Pengurus::findOrFail($id);
        return view('pengurus.edit', compact('pengurus'));
    }

    // Memproses pembaruan data dari satu baris form di halaman Kelola
    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $pengurus = Pengurus::findOrFail($id);

        // 1. Cek Jika Ada Upload Revisi SK (Berlaku Massal per Grup)
        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $filename = time() . '_sk_revisi_' . $id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/sk'), $filename);
            $skPath = 'uploads/sk/' . $filename;

            // Mass Update SK agar sinkron satu grup
            if (in_array($pengurus->kategori, ['Dewan', 'Inti', 'Bidang'])) {
                Pengurus::whereIn('kategori', ['Dewan', 'Inti', 'Bidang'])->update(['file_sk' => $skPath]);
            } elseif ($pengurus->kategori === 'Cabang') {
                Pengurus::where('kategori', 'Cabang')
                        ->where('nama_daerah', $pengurus->nama_daerah)
                        ->update(['file_sk' => $skPath]);
            }
        }

        // 2. Jika Form Khusus Profil Wilayah Cabang (Status & Alamat)
        if ($request->has('form_wilayah')) {
            Pengurus::where('kategori', 'Cabang')
                    ->where('nama_daerah', $pengurus->nama_daerah)
                    ->update([
                        'status_cabang' => $request->status_cabang,
                        'keterangan' => $request->keterangan
                    ]);
            return redirect('/pengurus/kelola')->with('success', 'Profil wilayah & SK Daerah berhasil diperbarui!');
        }

        // 3. Jika ini hanya form Upload SK Provinsi, hentikan proses di sini
        if ($request->has('form_khusus_sk')) {
            return redirect('/pengurus/kelola')->with('success', 'Dokumen SK Provinsi berhasil diperbarui secara massal!');
        }

        // 4. Proses Form Edit Baris Individual (Nama & Jabatan)
        $pengurus->update([
            'nama' => $request->nama ?: '-',
            'jabatan' => $request->jabatan ?: '-',
            'kategori' => $request->kategori,
            'keterangan' => $request->has('keterangan') && $request->kategori === 'Bidang' ? $request->keterangan : $pengurus->keterangan,
        ]);

        return redirect('/pengurus/kelola')->with('success', 'Data individu pengurus berhasil disimpan!');
    }

    // Memproses penghapusan satu baris data
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);
        Pengurus::findOrFail($id)->delete();
        return redirect('/pengurus/kelola')->with('success', 'Data pengurus berhasil dihapus!');
    }

    // Memproses penyimpanan data massal (Bulk Insert) ke database
    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $tipe = $request->tipe_form;

        if ($tipe === 'provinsi') {
            // 1. Unggah SK Provinsi
            $skPath = null;
            if ($request->hasFile('file_sk_provinsi')) {
                $file = $request->file('file_sk_provinsi');
                $filename = time() . '_sk_prov.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/sk'), $filename);
                $skPath = 'uploads/sk/' . $filename;
            }

            // 2. Looping data array provinsi
            if ($request->has('prov_kategori')) {
                foreach ($request->prov_kategori as $index => $kategori) {
                    $nama = !empty($request->prov_nama[$index]) ? $request->prov_nama[$index] : '-';
                    $jabatan = !empty($request->prov_jabatan[$index]) ? $request->prov_jabatan[$index] : '-';

                    // Jangan simpan jika keduanya kosong
                    if ($nama !== '-' || $jabatan !== '-') {
                        Pengurus::create([
                            'kategori' => $kategori,
                            'keterangan' => $request->prov_grup[$index] ?? null, // Digunakan untuk Nama Bidang
                            'jabatan' => $jabatan,
                            'nama' => $nama,
                            'file_sk' => $skPath,
                        ]);
                    }
                }
            }

        } elseif ($tipe === 'cabang') {
            // 1. Unggah SK Cabang
            $skPath = null;
            if ($request->hasFile('file_sk_cabang')) {
                $file = $request->file('file_sk_cabang');
                $filename = time() . '_sk_cabang_' . \Illuminate\Support\Str::slug($request->nama_daerah) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/sk'), $filename);
                $skPath = 'uploads/sk/' . $filename;
            }

            $daerah = $request->nama_daerah;
            $status = $request->status_cabang;
            $alamat = $request->keterangan;

            // 2. Looping data array pengurus cabang
            if ($request->has('cabang_nama')) {
                foreach ($request->cabang_nama as $index => $nama) {
                    if (!empty($nama)) {
                        Pengurus::create([
                            'nama' => $nama,
                            'jabatan' => $request->cabang_jabatan[$index] ?? '-',
                            'kategori' => 'Cabang',
                            'nama_daerah' => $daerah,
                            'status_cabang' => $status,
                            'keterangan' => $alamat, // Digunakan untuk Alamat Sekretariat
                            'file_sk' => $skPath,
                        ]);
                    }
                }
            }
        }

        return redirect('/pengurus')->with('success', 'Data kepengurusan berhasil ditambahkan secara massal!');
    }

    public function showCabang($slug)
    {
        // Ubah cara pengambilan data: Ambil semua yang punya nama daerah tanpa mempedulikan isi kolom tingkatan
        $semuaCabang = \App\Models\Pengurus::whereNotNull('nama_daerah')
                                            ->where('nama_daerah', '!=', '-')
                                            ->get();

        // Cocokkan slug dengan nama daerah
        $pengurusCabang = $semuaCabang->filter(function ($item) use ($slug) {
            return \Illuminate\Support\Str::slug($item->nama_daerah) === $slug;
        });

        // Jika gagal menemukan kecocokan data
        if ($pengurusCabang->isEmpty()) {
            abort(404, 'Data Pengurus Cabang tidak ditemukan. Periksa penulisan nama daerah di database.');
        }

        $infoDaerah = $pengurusCabang->first();
        $namaDaerah = $infoDaerah->nama_daerah;

        // Pisahkan Ketua dan Anggota lainnya
        $ketua = $pengurusCabang->filter(fn($p) => \Illuminate\Support\Str::contains(strtolower($p->jabatan), 'ketua'))->first();
        $anggota = $pengurusCabang->filter(fn($p) => !\Illuminate\Support\Str::contains(strtolower($p->jabatan), 'ketua'));

        return view('pengurus.cabang', compact('pengurusCabang', 'infoDaerah', 'namaDaerah', 'ketua', 'anggota'));
    }
}
