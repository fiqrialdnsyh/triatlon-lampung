<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignKontribusi;
use Illuminate\Http\Request;

class CampaignKontribusiController extends Controller
{
    public function store(Request $request, $campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'pesan' => 'nullable|string|max:1000',
        ];

        if ($campaign->tipe === 'Donasi') {
            $rules['nominal'] = 'required|numeric|min:10000';
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        if ($campaign->tipe === 'Kerjasama') {
            $rules['instansi'] = 'required|string|max:255';
        }

        if ($campaign->tipe === 'Campaign') {
            $rules['file_hasil'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
            $rules['link_hasil'] = 'nullable|url';
            $rules['pesan'] = 'required|string|max:1000';
        }

        $request->validate($rules, [
            'file_hasil.required' => 'Unggah foto sebagai bukti partisipasi kamu di campaign ini.',
            'pesan.required' => 'Ceritakan sedikit pengalaman/kontribusi kamu.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_donasi_' . (auth()->id() ?? 'guest') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/campaign/bukti'), $filename);
            $buktiPath = 'uploads/campaign/bukti/' . $filename;
        }

        $hasilPath = null;
        if ($request->hasFile('file_hasil')) {
            $file = $request->file('file_hasil');
            $filename = time() . '_hasil_' . (auth()->id() ?? 'guest') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/campaign/hasil'), $filename);
            $hasilPath = 'uploads/campaign/hasil/' . $filename;
        }

        CampaignKontribusi::create([
            'campaign_id' => $campaign->id,
            'user_id' => auth()->id(),
            'nama_lengkap' => $request->nama_lengkap,
            'instansi' => $request->instansi,
            'nominal' => $request->nominal,
            'bukti_transfer' => $buktiPath,
            'file_hasil' => $hasilPath,
            'link_hasil' => $request->link_hasil,
            'pesan' => $request->pesan,
            'tampilkan_publik' => $request->has('tampilkan_publik'),
            'status' => 'Menunggu',
        ]);

        $pesanSukses = match ($campaign->tipe) {
            'Donasi' => 'Terima kasih! Donasi Anda sedang kami verifikasi.',
            'Kerjasama' => 'Pengajuan Anda berhasil dikirim, tim kami akan segera menghubungi.',
            default => 'Hasil partisipasi kamu berhasil dikirim, menunggu verifikasi admin sebelum tampil di galeri.',
        };

        return back()->with('success', $pesanSukses);
    }

    public function terima($id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') abort(403);
        $kontribusi = CampaignKontribusi::findOrFail($id);
        $kontribusi->update(['status' => 'Diterima', 'alasan_ditolak' => null]);
        return back()->with('success', 'Kontribusi diterima.');
    }

    public function tolak(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->email !== 'admin@triatlon.test') abort(403);
        $kontribusi = CampaignKontribusi::findOrFail($id);
        $kontribusi->update([
            'status' => 'Ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);
        return back()->with('success', 'Kontribusi ditolak.');
    }
}
