<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $tipe = $request->query('tipe'); // filter tab: Donasi/Kerjasama/Campaign

        $campaigns = Campaign::where('status', 'Aktif')
            ->when($tipe, fn($q) => $q->where('tipe', $tipe))
            ->latest()
            ->get();

        return view('campaign.index', compact('campaigns', 'tipe'));
    }

    public function show($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $userKontribusi = auth()->check()
            ? $campaign->kontribusi()->where('user_id', auth()->id())->latest()->first()
            : null;

        $wallOfSupporters = $campaign->kontribusi()
            ->where('status', 'Diterima')
            ->where('tampilkan_publik', true)
            ->latest()
            ->take(20)
            ->get();

        // Khusus tipe Campaign: galeri hasil yang sudah diverifikasi & di-opt-in publik
        $galeriHasil = $campaign->tipe === 'Campaign'
            ? $campaign->kontribusi()->where('status', 'Diterima')->where('tampilkan_publik', true)->latest()->get()
            : collect();

        $jumlahPartisipan = $campaign->tipe === 'Campaign'
            ? $campaign->kontribusi()->where('status', 'Diterima')->count()
            : 0;

        $allKontribusi = [];
        if (auth()->check() && auth()->user()->email === 'admin@triatlon.test') {
            $allKontribusi = $campaign->kontribusi()->latest()->get();
        }

        return view('campaign.show', compact('campaign', 'userKontribusi', 'wallOfSupporters', 'allKontribusi', 'galeriHasil', 'jumlahPartisipan'));
    }

    // --- ADMIN ---
    public function create()
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') abort(403);
        return view('campaign.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:Donasi,Kerjasama,Campaign',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'poster' => 'nullable|image|max:2048',

            // Wajib hanya jika tipe = Donasi
            'target_dana' => 'required_if:tipe,Donasi|nullable|numeric|min:1',
            'rekening' => 'required_if:tipe,Donasi|nullable|string|max:255',

            // Wajib hanya jika tipe = Kerjasama
            'link_wa' => 'required_if:tipe,Kerjasama|nullable|url',
        ], [
            'target_dana.required_if' => 'Target dana wajib diisi untuk campaign bertipe Donasi.',
            'rekening.required_if' => 'Rekening tujuan wajib diisi untuk campaign bertipe Donasi.',
            'link_wa.required_if' => 'Link WhatsApp wajib diisi untuk campaign bertipe Kerjasama.',
        ]);

        $campaign = new Campaign();
        $campaign->judul = $request->judul;
        $campaign->slug = Str::slug($request->judul) . '-' . time();
        $campaign->tipe = $request->tipe;
        $campaign->deskripsi = $request->deskripsi;

        // Simpan field sesuai tipe saja, biar data tidak "nyasar" antar tipe
        $campaign->target_dana = $request->tipe === 'Donasi' ? $request->target_dana : null;
        $campaign->rekening = $request->tipe === 'Donasi' ? $request->rekening : null;
        $campaign->link_wa = $request->tipe === 'Kerjasama' ? $request->link_wa : null;

        $campaign->tanggal_mulai = $request->tanggal_mulai;
        $campaign->tanggal_selesai = $request->tanggal_selesai;
        $campaign->status = 'Aktif';

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_campaign.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/campaign/poster'), $filename);
            $campaign->poster = 'uploads/campaign/poster/' . $filename;
        }

        $campaign->save();

        return redirect('/campaign')->with('success', 'Campaign berhasil dipublikasikan!');
    }
}
