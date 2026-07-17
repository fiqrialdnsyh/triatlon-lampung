<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenuePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class VenueController extends Controller
{
    // Menampilkan halaman katalog venue untuk semua pengunjung
    public function index(Request $request)
    {
        $query = Venue::with('photos')->latest();

        // Logika Filter Daerah
        if ($request->has('daerah') && $request->daerah != '') {
            $query->where('daerah', strtoupper($request->daerah));
        }

        $venues = $query->get();

        return view('venue.index', compact('venues'));
    }

    // Menampilkan halaman tambah venue khusus admin
    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        return view('venue.create');
    }

    // Memproses penyimpanan data venue baru
    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'nama' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'alamat' => 'required|string',
            'fasilitas.*' => 'nullable|string',
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072' // Max 3MB per foto
        ]);

        $venue = Venue::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama) . '-' . time(),
            'daerah' => strtoupper($request->daerah),
            'tingkat' => $request->tingkat,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => array_filter($request->fasilitas ?? []), // Menghapus form fasilitas yang tidak diisi
            'rute_renang' => $request->rute_renang,
            'rute_sepeda' => $request->rute_sepeda,
            'rute_lari' => $request->rute_lari,
            'area_transisi' => $request->area_transisi,
            'link_maps' => $request->link_maps,
        ]);

        // Proses Multi-Upload Foto
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/venues'), $filename);

                VenuePhoto::create([
                    'venue_id' => $venue->id,
                    'path_foto' => 'uploads/venues/' . $filename
                ]);
            }
        }

        return redirect()->route('venue.index')->with('success', 'Venue baru berhasil ditambahkan.');
    }

    // Menampilkan halaman detail spesifik dari satu venue
    public function show($slug)
    {
        $venue = Venue::where('slug', $slug)->with('photos')->firstOrFail();

        return view('venue.show', compact('venue'));
    }

    // Menampilkan halaman edit venue khusus admin
    public function edit($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        $venue = Venue::with('photos')->findOrFail($id);

        return view('venue.edit', compact('venue'));
    }

    // Memproses pembaruan data venue
    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        $venue = Venue::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'alamat' => 'required|string',
            'fasilitas.*' => 'nullable|string',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $venue->update([
            'nama' => $request->nama,
            'daerah' => strtoupper($request->daerah),
            'tingkat' => $request->tingkat,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => array_filter($request->fasilitas ?? []),
            'rute_renang' => $request->rute_renang,
            'rute_sepeda' => $request->rute_sepeda,
            'rute_lari' => $request->rute_lari,
            'area_transisi' => $request->area_transisi,
            'link_maps' => $request->link_maps,
        ]);

        // Tambah foto baru jika ada yang diupload saat proses edit
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $filename = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/venues'), $filename);

                VenuePhoto::create([
                    'venue_id' => $venue->id,
                    'path_foto' => 'uploads/venues/' . $filename
                ]);
            }
        }

        return redirect()->route('venue.index')->with('success', 'Data Venue berhasil diperbarui.');
    }

    // Fungsi khusus menghapus SATU foto spesifik via tombol di halaman edit
    public function destroyFoto($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        $foto = VenuePhoto::findOrFail($id);

        // Hapus berkas fisik dari server
        if (File::exists(public_path($foto->path_foto))) {
            File::delete(public_path($foto->path_foto));
        }

        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus dari galeri venue.');
    }

    // Fungsi khusus menghapus KESELURUHAN data Venue (jika dibutuhkan)
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);

        $venue = Venue::with('photos')->findOrFail($id);

        // Hapus seluruh berkas foto fisik dari server
        foreach($venue->photos as $foto) {
            if (File::exists(public_path($foto->path_foto))) {
                File::delete(public_path($foto->path_foto));
            }
        }

        // Ini otomatis akan menghapus data di tabel venue_photos jika relasi cascade on delete dikonfigurasi
        $venue->delete();

        return redirect()->route('venue.index')->with('success', 'Seluruh data Venue berhasil dihapus permanen.');
    }
}
