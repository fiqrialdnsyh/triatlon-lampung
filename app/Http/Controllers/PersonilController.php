<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PersonilController extends Controller
{
    public function index()
    {
        $atlets = Personil::where('kategori', 'Atlet')->latest()->get();
        $pelatihs = Personil::where('kategori', 'Pelatih')->latest()->get();
        $wasits = Personil::where('kategori', 'Wasit')->latest()->get();

        return view('personil.index', compact('atlets', 'pelatihs', 'wasits'));
    }

    public function create()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/personil');
        }
        return view('personil.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $request->validate([
            'kategori' => 'required|in:Atlet,Pelatih,Wasit',
            'nama' => 'required|string|max:255',
            'asal_daerah' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sertifikat_lisensi' => 'nullable|mimes:pdf|max:5120',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_foto_' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/personil/foto'), $filename);
            $fotoPath = 'uploads/personil/foto/' . $filename;
        }

        $sertifikatPath = null;
        if ($request->hasFile('sertifikat_lisensi')) {
            $file = $request->file('sertifikat_lisensi');
            $filename = time() . '_cert_' . Str::slug($request->nama) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/personil/sertifikat'), $filename);
            $sertifikatPath = 'uploads/personil/sertifikat/' . $filename;
        }

        Personil::create([
            'kategori' => $request->kategori,
            'nama' => $request->nama,
            'asal_daerah' => $request->asal_daerah,
            'foto' => $fotoPath,
            'kontak' => $request->kontak,
            // Khusus Pelatih & Wasit
            'tingkat_lisensi' => $request->kategori !== 'Atlet' ? $request->tingkat_lisensi : null,
            'sertifikat_lisensi' => $request->kategori !== 'Atlet' ? $sertifikatPath : null,
            // Khusus Atlet
            'ttl' => $request->kategori === 'Atlet' ? $request->ttl : null,
            'umur' => $request->kategori === 'Atlet' ? $request->umur : null,
            'jenis_identitas' => $request->kategori === 'Atlet' ? $request->jenis_identitas : null,
            'nomor_identitas' => $request->kategori === 'Atlet' ? $request->nomor_identitas : null,
        ]);

        return redirect('/personil')->with('success', 'Data sdm baru berhasil didaftarkan');
    }

    public function kelola()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/personil');
        }
        $atlets = Personil::where('kategori', 'Atlet')->latest()->get();
        $pelatihs = Personil::where('kategori', 'Pelatih')->latest()->get();
        $wasits = Personil::where('kategori', 'Wasit')->latest()->get();

        return view('personil.kelola', compact('atlets', 'pelatihs', 'wasits'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $personil = Personil::findOrFail($id);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_foto_' . Str::slug($personil->nama) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/personil/foto'), $filename);
            $personil->foto = 'uploads/personil/foto/' . $filename;
        }

        if ($request->hasFile('sertifikat_lisensi')) {
            $file = $request->file('sertifikat_lisensi');
            $filename = time() . '_cert_' . Str::slug($personil->nama) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/personil/sertifikat'), $filename);
            $personil->sertifikat_lisensi = 'uploads/personil/sertifikat/' . $filename;
        }

        $personil->nama = $request->nama;
        $personil->asal_daerah = $request->asal_daerah;
        $personil->kontak = $request->kontak;

        if ($personil->kategori === 'Atlet') {
            $personil->ttl = $request->ttl;
            $personil->umur = $request->umur;
            $personil->jenis_identitas = $request->jenis_identitas;
            $personil->nomor_identitas = $request->nomor_identitas;
        } else {
            $personil->tingkat_lisensi = $request->tingkat_lisensi;
        }

        $personil->save();
        return redirect('/personil/kelola')->with('success', 'Data sdm berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) return abort(403);

        $personil = Personil::findOrFail($id);
        $personil->delete();

        return redirect('/personil/kelola')->with('success', 'Data sdm berhasil dihapus');
    }
}
