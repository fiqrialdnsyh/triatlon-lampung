@extends('layouts.main')

@section('title', 'Open Tournament - FTI LAMPUNG')

@section('content')
    <section class="bg-navy pt-24 pb-16 px-8 md:px-16 text-center relative overflow-hidden">
        <div class="relative z-10">
            <span class="text-yellow text-sm font-black tracking-widest uppercase mb-4 block">Pendaftaran Umum</span>
            <h1 class="font-oswald text-white text-4xl md:text-5xl font-bold uppercase tracking-wide mb-4">OPEN TOURNAMENT</h1>
            <div class="w-20 h-1 bg-yellow mx-auto mb-4"></div>
            <p class="text-white/70 font-semibold max-w-2xl mx-auto text-sm">Direktori resmi agenda kejuaraan terbuka Federasi Triathlon Indonesia Wilayah Provinsi Lampung.</p>
        </div>

        <!-- TOMBOL RIWAYAT KHUSUS ATLET (PESERTA) -->
        @auth
            @if(auth()->user()->isAdmin())
                <div class="absolute top-24 right-6 md:top-8 md:right-16 z-20">
                    <a href="{{ route('event.open.history') }}" class="bg-yellow text-navy px-5 py-2.5 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-white transition-colors shadow-md flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Saya
                    </a>
                </div>
            @endif
        @endauth
    </section>

    @auth
        @if(auth()->user()->isAdmin())
            <div class="bg-white border-b border-gray-200 py-4 px-4 md:px-16 z-20 relative shadow-sm">
                <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-navy font-black text-xs uppercase tracking-widest flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2.5"></span> Mode Administrator Aktif
                    </p>
                    <div class="flex gap-2 w-full md:w-auto">
                        <a href="{{ route('event.open.create') }}" class="flex-1 md:flex-none text-center bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-yellow/80 transition-colors shadow-sm">
                            + TAMBAH EVENT OPEN
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            @if(session('success'))
                <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($events as $event)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden flex flex-col hover:-translate-y-1 transition-transform duration-300">

                        <div class="h-52 bg-gray-100 relative overflow-hidden">
                            @if($event->poster)
                                <img src="{{ asset($event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-navy/20 bg-navy/5">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif

                            <div class="absolute top-4 right-4 px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-wider
                                {{ $event->status == 'Buka' ? 'bg-green-600 text-white' : ($event->status == 'Tutup' ? 'bg-red-500 text-white' : 'bg-gray-500 text-white') }}">
                                {{ $event->status }}
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-black text-navy text-lg uppercase leading-tight mb-4 line-clamp-2 min-h-[44px] flex items-center">{{ $event->judul }}</h3>

                                <div class="space-y-2 border-t border-gray-100 pt-4 mb-6 text-[11px] font-semibold text-navy/70">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $event->lokasi }}
                                    </div>
                                </div>
                            </div>

                            <div class="w-full">
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="{{ route('event.open.kelola') }}" class="text-center bg-gray-100 text-navy py-2.5 rounded-lg font-black text-[10px] uppercase tracking-wider border border-gray-200 hover:bg-gray-200 transition-colors">
                                                Edit Form
                                            </a>
                                            <a href="{{ route('event.open.show', $event->slug) }}" class="text-center bg-navy text-yellow py-2.5 rounded-lg font-black text-[10px] uppercase tracking-wider hover:bg-navy/90 transition-colors">
                                                Verifikasi Peserta
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route('event.open.show', $event->slug) }}" class="block w-full text-center bg-navy text-yellow py-3 rounded-xl font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-sm">
                                            Detail & Registrasi
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('event.open.show', $event->slug) }}" class="block w-full text-center bg-navy text-yellow py-3 rounded-xl font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-sm">
                                        Detail & Registrasi
                                    </a>
                                @endauth
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full border-2 border-dashed border-gray-300 p-12 rounded-2xl text-center bg-white">
                        <p class="font-black text-navy/40 uppercase text-sm tracking-wider">Belum ada agenda turnamen terbuka yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
@endsection
