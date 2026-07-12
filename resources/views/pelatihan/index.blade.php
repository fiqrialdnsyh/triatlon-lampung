@extends('layouts.main')

@section('title', 'Daftar Pelatihan - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-8 md:px-16 text-center" data-aos="fade-down">
        <h1 class="font-oswald text-white text-4xl md:text-5xl font-bold uppercase tracking-wide mb-4">PROGRAM PELATIHAN</h1>
        <p class="text-white/70 font-semibold max-w-2xl mx-auto text-sm">Tingkatkan kapasitas dan lisensi Anda sebagai
            pelatih profesional melalui program sertifikasi resmi Triatlon Provinsi Lampung.</p>
    </section>

    <section class="bg-navy pb-24 px-8 md:px-16" data-aos="fade-up">

        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-white text-lg font-black uppercase tracking-wider">Daftar Kelas Tersedia</h2>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                @auth
                    @if (auth()->user()->email !== 'admin@triatlon.test')
                        <a href="{{ route('pelatihan.history') }}"
                            class="w-full sm:w-auto text-center bg-transparent text-white border border-white/30 hover:border-yellow hover:text-yellow px-5 py-2.5 font-black text-xs uppercase tracking-wider rounded-md transition-colors shadow-lg">
                            Riwayat Pendaftaran Saya
                        </a>
                    @else
                        <a href="{{ url('/pelatihan/create') }}"
                            class="w-full sm:w-auto text-center bg-yellow text-navy px-5 py-2.5 font-black text-xs uppercase tracking-wider rounded-md hover:bg-white transition-colors shadow-lg">
                            + TAMBAH PELATIHAN BARU
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">

            @forelse ($pelatihans as $item)
                @php
                    // Logika Status: Tutup jika admin set 'Tutup' ATAU jika tanggal hari ini melebihi batas daftar
                    $isTutup =
                        $item->status == 'Tutup' ||
                        \Carbon\Carbon::now('Asia/Jakarta')
                            ->startOfDay()
                            ->gt(\Carbon\Carbon::parse($item->batas_pendaftaran));
                @endphp

                <div
                    class="training-card bg-cream p-8 rounded-[2rem] flex flex-col justify-between shadow-lg {{ $isTutup ? 'opacity-80' : '' }}">
                    <div>
                        @if (!$isTutup)
                            <div
                                class="inline-block bg-navy text-yellow text-[10px] font-black uppercase px-3 py-1 rounded-sm mb-4">
                                Pendaftaran Dibuka</div>
                        @else
                            <div
                                class="inline-block bg-gray-400 text-white text-[10px] font-black uppercase px-3 py-1 rounded-sm mb-4">
                                Pendaftaran Ditutup</div>
                        @endif

                        <h3 class="text-2xl font-black uppercase leading-tight mb-3 text-navy">{{ $item->judul }}</h3>
                        <p class="text-sm font-semibold text-navy/70 tracking-wide leading-relaxed mb-6 line-clamp-3">
                            {{ $item->deskripsi }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-navy/10 pt-6 mt-2">
                        <div>
                            <p class="text-xs font-bold text-navy/70">Mulai Pelatihan:</p>
                            <p class="text-sm font-black text-navy">
                                {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="flex space-x-2">
                            @auth
                                @if (auth()->user()->email == 'admin@triatlon.test')
                                    <a href="{{ route('pelatihan.edit', $item->id) }}"
                                        class="bg-gray-300 text-navy px-4 py-2.5 font-black text-xs uppercase rounded-sm hover:bg-gray-400 transition-colors">EDIT</a>
                                @endif
                            @endauth

                            @if (!$isTutup || (auth()->check() && auth()->user()->email == 'admin@triatlon.test'))
                                <a href="{{ url('/pelatihan/' . $item->id) }}"
                                    class="bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-sm hover:bg-navy hover:text-yellow transition-colors">
                                    DETAIL
                                </a>
                            @else
                                <button disabled
                                    class="bg-gray-300 text-gray-500 px-6 py-2.5 font-black text-xs uppercase rounded-sm cursor-not-allowed">
                                    DITUTUP
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white/5 rounded-2xl border border-white/10">
                    <p class="text-white/70 font-semibold text-sm uppercase tracking-widest">Belum ada program pelatihan
                        yang dipublikasikan saat ini.</p>
                </div>
            @endforelse

        </div>
    </section>
@endsection
