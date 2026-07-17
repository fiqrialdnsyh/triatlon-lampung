@extends('layouts.main')
@section('title', $campaign->judul)

@section('content')
<section class="py-12 px-4 sm:px-8 md:px-16 min-h-screen bg-[#0B1528]">
    <div class="max-w-6xl mx-auto">

        <a href="{{ url('/campaign') }}" class="inline-flex items-center text-yellow text-xs font-bold uppercase tracking-wider hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            KEMBALI KE DAFTAR CAMPAIGN
        </a>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col lg:flex-row gap-10">
            <div class="lg:w-3/5 text-white">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-yellow text-navy text-[9px] font-black uppercase px-2.5 py-1 rounded">{{ $campaign->tipe }}</span>
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-wider">{{ $campaign->status }}</span>
                </div>
                <h1 class="font-oswald text-3xl md:text-4xl font-bold uppercase tracking-wide mb-4 leading-tight">{{ $campaign->judul }}</h1>

                @if($campaign->poster)
                    <img src="{{ asset($campaign->poster) }}" class="w-full h-56 md:h-80 object-cover rounded-2xl mb-6 shadow-lg">
                @endif

                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-white/5 border border-white/10 p-3 rounded-xl text-center">
                        <p class="text-[9px] font-black text-white/50 uppercase">Mulai</p>
                        <p class="text-xs font-black text-white mt-1">{{ \Carbon\Carbon::parse($campaign->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 p-3 rounded-xl text-center">
                        <p class="text-[9px] font-black text-white/50 uppercase">Berakhir</p>
                        <p class="text-xs font-black text-white mt-1">{{ $campaign->tanggal_selesai ? \Carbon\Carbon::parse($campaign->tanggal_selesai)->translatedFormat('d M Y') : 'Tanpa Batas' }}</p>
                    </div>
                </div>

                <h4 class="text-yellow text-sm font-black uppercase mb-2">Tentang Campaign Ini</h4>
                <p class="text-sm font-semibold text-white/80 leading-relaxed whitespace-pre-line mb-8">{{ $campaign->deskripsi }}</p>

                @if($campaign->tipe === 'Donasi' && $wallOfSupporters->count())
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <h4 class="text-yellow text-xs font-black uppercase mb-3">Dukungan Terbaru</h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                            @foreach($wallOfSupporters as $s)
                                <div class="flex justify-between items-center text-xs bg-white/5 rounded-lg px-3 py-2">
                                    <span class="font-bold text-white">{{ $s->nama_lengkap }}</span>
                                    <span class="font-black text-yellow">Rp {{ number_format($s->nominal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:w-2/5">
                <div class="bg-cream p-6 md:p-8 rounded-[2rem] shadow-xl sticky top-24">

                    @if($campaign->tipe === 'Donasi')
                        <div class="mb-6">
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden mb-2">
                                <div class="h-full bg-navy" style="width: {{ $campaign->persen_tercapai }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs font-black text-navy">
                                <span>Rp {{ number_format($campaign->dana_terkumpul, 0, ',', '.') }}</span>
                                <span class="text-navy/40">dari Rp {{ number_format($campaign->target_dana, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] font-bold text-navy/50 mt-1">{{ $campaign->jumlah_donatur }} orang telah berdonasi &middot; {{ $campaign->persen_tercapai }}% tercapai</p>
                        </div>
                        <p class="text-xs font-bold text-navy/70 mb-4">Rekening: {{ $campaign->rekening }}</p>
                    @endif

                    @if($campaign->tipe === 'Kerjasama' && $campaign->link_wa)
                        <a href="{{ $campaign->link_wa }}" target="_blank" class="block text-center bg-green-600 text-white py-3 rounded-xl font-black text-xs uppercase mb-4 hover:bg-green-700 transition">Hubungi via WhatsApp</a>
                    @endif

                    @if($campaign->tipe === 'Campaign')
                        <div class="mb-6 text-center">
                            <p class="text-3xl font-black text-navy">{{ $jumlahPartisipan }}</p>
                            <p class="text-[10px] font-black text-navy/50 uppercase tracking-widest">Orang Telah Berpartisipasi</p>
                        </div>
                    @endif

                    @if($userKontribusi)
                        <div class="mb-5 rounded-xl border-2 p-4 {{ $userKontribusi->status === 'Diterima' ? 'bg-green-50 border-green-200' : ($userKontribusi->status === 'Ditolak' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">
                            <p class="text-[10px] font-black uppercase tracking-widest mb-1 {{ $userKontribusi->status === 'Diterima' ? 'text-green-700' : ($userKontribusi->status === 'Ditolak' ? 'text-red-700' : 'text-yellow-700') }}">
                                Status Kontribusi Kamu: {{ $userKontribusi->status }}
                            </p>
                            @if($userKontribusi->status === 'Ditolak' && $userKontribusi->alasan_ditolak)
                                <p class="text-xs font-semibold text-red-600">Alasan: {{ $userKontribusi->alasan_ditolak }}</p>
                            @elseif($userKontribusi->status === 'Menunggu')
                                <p class="text-xs font-semibold text-navy/60">Sedang menunggu verifikasi admin.</p>
                            @else
                                <p class="text-xs font-semibold text-navy/60">Terima kasih atas kontribusimu!</p>
                            @endif
                        </div>
                    @endif

                    @if(in_array($campaign->tipe, ['Donasi', 'Kerjasama', 'Campaign']) && (!$userKontribusi || $userKontribusi->status === 'Ditolak'))
                        @auth
                            <form action="{{ route('campaign.kontribusi.store', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf

                                @if($campaign->tipe === 'Campaign')
                                    <h4 class="font-black text-navy uppercase text-sm mb-1">Submit Hasil Partisipasi</h4>
                                    <p class="text-[11px] font-semibold text-navy/50 mb-3">Unggah dokumentasi kegiatan/hasil kamu terkait campaign ini.</p>
                                @endif

                                <div>
                                    <label class="block text-xs font-bold text-navy mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-yellow" required>
                                </div>

                                @if($campaign->tipe === 'Kerjasama')
                                    <div>
                                        <label class="block text-xs font-bold text-navy mb-1.5">Instansi/Perusahaan</label>
                                        <input type="text" name="instansi" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-yellow" required>
                                    </div>
                                @endif

                                @if($campaign->tipe === 'Donasi')
                                    <div>
                                        <label class="block text-xs font-bold text-navy mb-1.5">Nominal Donasi (Rp)</label>
                                        <input type="number" name="nominal" min="10000" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-yellow" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-navy mb-1.5">Bukti Transfer</label>
                                        <input type="file" name="bukti_transfer" accept="image/*" class="block w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow" required>
                                    </div>
                                @endif

                                @if($campaign->tipe === 'Campaign')
                                    <div>
                                        <label class="block text-xs font-bold text-navy mb-1.5">Foto Bukti/Hasil Partisipasi</label>
                                        <input type="file" name="file_hasil" accept="image/*" class="block w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-navy mb-1.5">Link Postingan Medsos (opsional)</label>
                                        <input type="url" name="link_hasil" placeholder="https://instagram.com/..." class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-yellow">
                                    </div>
                                @endif

                                @if(in_array($campaign->tipe, ['Donasi', 'Campaign']))
                                    <label class="flex items-center gap-2 text-[11px] font-bold text-navy/60">
                                        <input type="checkbox" name="tampilkan_publik" value="1" class="rounded">
                                        Tampilkan {{ $campaign->tipe === 'Donasi' ? 'nama saya di daftar dukungan' : 'hasil saya di galeri' }} publik
                                    </label>
                                @endif

                                <div>
                                    <label class="block text-xs font-bold text-navy mb-1.5">
                                        {{ $campaign->tipe === 'Campaign' ? 'Ceritakan pengalamanmu' : 'Pesan (opsional)' }}
                                    </label>
                                    <textarea name="pesan" rows="3" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-yellow" {{ $campaign->tipe === 'Campaign' ? 'required' : '' }}></textarea>
                                </div>

                                <button type="submit" class="w-full bg-yellow text-navy py-3.5 rounded-xl font-black text-sm uppercase hover:bg-navy hover:text-yellow transition shadow-lg">
                                    @if($campaign->tipe === 'Donasi') Kirim Donasi
                                    @elseif($campaign->tipe === 'Kerjasama') Ajukan Kerjasama
                                    @else Submit Hasil
                                    @endif
                                </button>
                            </form>
                        @else
                            <a href="{{ url('/login') }}" class="block text-center bg-gray-400 text-white py-3.5 rounded-xl font-black text-sm uppercase">Login Dulu</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>

        <!-- GALERI HASIL CAMPAIGN -->
        @if($campaign->tipe === 'Campaign' && $galeriHasil->count())
            <div class="mt-12">
                <h3 class="font-oswald text-white text-xl md:text-2xl font-bold uppercase tracking-wide mb-6">Galeri Hasil Partisipasi</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($galeriHasil as $g)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg group">
                            @if($g->file_hasil)
                                <a href="{{ asset($g->file_hasil) }}" target="_blank" class="block h-36 overflow-hidden">
                                    <img src="{{ asset($g->file_hasil) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @endif
                            <div class="p-3">
                                <p class="font-black text-navy text-xs uppercase truncate">{{ $g->nama_lengkap }}</p>
                                @if($g->pesan)
                                    <p class="text-[10px] font-semibold text-navy/60 mt-1 line-clamp-2">{{ $g->pesan }}</p>
                                @endif
                                @if($g->link_hasil)
                                    <a href="{{ $g->link_hasil }}" target="_blank" class="inline-flex items-center gap-1 text-[9px] font-black text-blue-600 uppercase mt-2 hover:underline">
                                        Lihat Postingan
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Panel Admin Verifikasi -->
        @if(auth()->check() && auth()->user()->isAdmin() && count($allKontribusi))
            <div class="mt-12 bg-[#F8F9FA] rounded-[2rem] p-6 md:p-8">
                <h3 class="font-black text-navy uppercase text-sm mb-4">Verifikasi Kontribusi</h3>
                <div class="space-y-3">
                    @foreach($allKontribusi as $k)
                        <div class="bg-white p-4 rounded-xl border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                @if($k->file_hasil)
                                    <img src="{{ asset($k->file_hasil) }}" class="w-14 h-14 object-cover rounded-lg shrink-0">
                                @endif
                                <div>
                                    <p class="font-black text-navy text-sm">{{ $k->nama_lengkap }} @if($k->instansi) — {{ $k->instansi }} @endif</p>
                                    @if($k->nominal)<p class="text-xs font-bold text-navy/60">Rp {{ number_format($k->nominal, 0, ',', '.') }}</p>@endif
                                    @if($k->pesan)<p class="text-[10px] text-navy/50 font-semibold line-clamp-1 max-w-xs">{{ $k->pesan }}</p>@endif
                                    @if($k->bukti_transfer)<a href="{{ asset($k->bukti_transfer) }}" target="_blank" class="text-[10px] text-blue-600 underline font-bold">Lihat Bukti Transfer</a>@endif
                                    @if($k->file_hasil)<a href="{{ asset($k->file_hasil) }}" target="_blank" class="text-[10px] text-blue-600 underline font-bold block">Lihat Foto Hasil</a>@endif
                                </div>
                            </div>
                            @if($k->status === 'Menunggu')
                                <div class="flex gap-2 shrink-0">
                                    <form action="{{ route('kontribusi.terima', $k->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-green-500 text-white text-[10px] font-black uppercase px-3 py-2 rounded">Terima</button>
                                    </form>
                                    <form action="{{ route('kontribusi.tolak', $k->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-red-500 text-white text-[10px] font-black uppercase px-3 py-2 rounded">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-[10px] font-black uppercase px-3 py-1.5 rounded shrink-0 {{ $k->status === 'Diterima' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $k->status }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
