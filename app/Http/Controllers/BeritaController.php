<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BeritaController extends Controller
{
    // Menampilkan daftar berita untuk publik
    public function index()
    {
        $beritas = Berita::latest()->get();
        return view('berita.index', compact('beritas'));
    }

    // Menampilkan form buat berita (Admin Only)
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return redirect('/berita');
        }
        return view('berita.create');
    }

    // Menyimpan berita ke database
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'penulis' => 'required|string',
            'foto_cover' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'konten' => 'required',
        ]);

        $coverPath = null;
        if ($request->hasFile('foto_cover')) {
            $file = $request->file('foto_cover');
            $filename = time() . '_cover_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $coverPath = 'uploads/berita/' . $filename;
        }

        Berita::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'penulis' => $request->penulis,
            'foto_cover' => $coverPath,
            'konten' => $request->konten,
            'tags' => $request->tags,
        ]);

        return redirect('/berita')->with('success', 'Berita berhasil dipublikasikan!');
    }

    // Menampilkan detail satu berita
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('berita.show', compact('berita'));
    }

    // Halaman khusus kelola list berita (Admin Only)
    public function kelola()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') {
            return redirect('/berita');
        }
        $beritas = Berita::latest()->get();
        return view('berita.kelola', compact('beritas'));
    }

    // Menampilkan form edit berita
    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);
        $berita = Berita::findOrFail($id);
        return view('berita.edit', compact('berita'));
    }

    // Memproses pembaruan berita
    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'penulis' => 'required|string',
            'foto_cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'konten' => 'required',
        ]);

        $coverPath = $berita->foto_cover;
        // Jika ada unggahan foto cover baru, hapus foto lama dan simpan yang baru
        if ($request->hasFile('foto_cover')) {
            if (File::exists(public_path($berita->foto_cover))) {
                File::delete(public_path($berita->foto_cover));
            }
            $file = $request->file('foto_cover');
            $filename = time() . '_cover_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/berita'), $filename);
            $coverPath = 'uploads/berita/' . $filename;
        }

        $berita->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'penulis' => $request->penulis,
            'foto_cover' => $coverPath,
            'konten' => $request->konten,
            'tags' => $request->tags,
        ]);

        return redirect('/berita/kelola')->with('success', 'Berita berhasil diperbarui!');
    }

    // Memproses penghapusan berita
    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') return abort(403);

        $berita = Berita::findOrFail($id);

        // Hapus file fisik foto cover dari folder public/uploads/berita sebelum menghapus baris database
        if (File::exists(public_path($berita->foto_cover))) {
            File::delete(public_path($berita->foto_cover));
        }

        $berita->delete();

        return redirect('/berita/kelola')->with('success', 'Berita berhasil dihapus!');
    }
}
