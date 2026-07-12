@extends('layouts.main')
@section('title', 'Kalender Event - FTI LAMPUNG')

@section('content')
<section class="bg-navy pt-20 pb-16 px-6 md:px-16 relative overflow-hidden border-b-4 border-yellow">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="relative z-10 max-w-4xl mx-auto text-center">
        <span class="text-yellow text-xs font-black tracking-widest uppercase mb-3 block">Satu Pintu Registrasi</span>
        <h1 class="font-oswald text-white text-4xl md:text-5xl font-bold uppercase tracking-wide mb-4">KALENDER KEJUARAAN</h1>
        <div class="w-16 h-1 bg-yellow mx-auto mb-6"></div>
        <p class="text-white/70 font-medium text-sm leading-relaxed max-w-xl mx-auto">Pantau jadwal kejuaraan FTI Lampung. Akses pendaftaran individu (Open) dan delegasi resmi kontingen (Kejurnas) dalam satu pintu.</p>

        <div class="mt-8 flex flex-wrap justify-center items-center gap-3">
            @if(auth()->check() && auth()->user()->email == 'admin@triatlon.test')
                <a href="{{ route('main_event.create') }}" class="inline-flex items-center gap-2 bg-yellow text-navy px-6 py-3 rounded-lg font-black text-xs uppercase hover:bg-white transition-all shadow-lg hover:shadow-yellow/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Terbitkan Event Baru
                </a>
            @endif

            @auth
                @if(auth()->user()->email !== 'admin@triatlon.test')
                    <a href="{{ auth()->user()->role == 'kontingen' ? route('event.kejurnas.history') : route('event.open.history') }}"
                       class="inline-flex items-center gap-2 bg-transparent border border-white/20 text-white px-6 py-3 rounded-lg font-black text-xs uppercase hover:border-yellow hover:text-yellow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Histori & Tiket Saya
                    </a>
                @endif
            @endauth
        </div>
    </div>
</section>

<section class="bg-[#EDEFF3] py-16 px-6 md:px-16 min-h-screen">
    <div class="max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($mainEvents as $event)
                @php
                    $isPast = \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->isPast();
                @endphp
                <div class="group bg-white rounded-3xl border border-gray-200 shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden {{ $isPast ? 'opacity-70' : '' }}">

                    <div class="h-56 bg-navy relative overflow-hidden">
                        @if($event->poster)
                            <img src="{{ asset($event->poster) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/20">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-navy/90 via-navy/10 to-transparent"></div>

                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            @if($event->is_open_active)
                                <span class="bg-yellow text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest shadow-md w-fit">Jalur Open</span>
                            @endif
                            @if($event->is_kejurnas_active)
                                <span class="bg-white text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest shadow-md w-fit border border-navy/10">Jalur Kejurnas</span>
                            @endif
                        </div>

                        @if($isPast)
                            <span class="absolute top-4 right-4 bg-black/60 text-white px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest backdrop-blur-sm">Selesai</span>
                        @endif

                        <div class="absolute bottom-4 left-4 flex items-end gap-3">
                            <div class="bg-yellow px-2.5 py-1.5 rounded-lg shadow-md text-center leading-none">
                                <p class="text-navy text-[9px] font-black uppercase">{{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('M') }}</p>
                                <p class="text-navy text-base font-black">{{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d') }}</p>
                            </div>
                            <p class="text-white text-[10px] font-bold uppercase tracking-wide pb-1.5 drop-shadow">
                                {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('l, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-black text-navy uppercase text-xl leading-tight mb-3 line-clamp-2 min-h-[56px] group-hover:text-navy/70 transition-colors">
                                {{ $event->judul }}
                            </h3>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-navy/60 mb-6">
                                <svg class="w-4 h-4 text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="line-clamp-1">{{ $event->lokasi }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            @if(auth()->check() && auth()->user()->email == 'admin@triatlon.test')
                                <div class="grid gap-2 {{ $event->is_open_active && $event->is_kejurnas_active ? 'grid-cols-2' : 'grid-cols-1' }}">
                                    @if($event->is_open_active && $subOpen = $event->subEvents->where('tipe', 'Open')->first())
                                        <a href="{{ route('event.open.show', $subOpen->slug) }}" class="text-center bg-navy text-yellow hover:bg-yellow hover:text-navy border border-navy py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                            Validasi Open
                                        </a>
                                    @endif
                                    @if($event->is_kejurnas_active && $subKejurnas = $event->subEvents->where('tipe', 'Kejurnas')->first())
                                        <a href="{{ route('event.kejurnas.show', $subKejurnas->slug) }}" class="text-center bg-white text-navy hover:bg-navy hover:text-yellow border border-navy py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                            Validasi Kejurnas
                                        </a>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('main_event.show', $event->slug) }}" class="flex items-center justify-center gap-2 w-full bg-navy text-yellow hover:bg-yellow hover:text-navy py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">
                                    Lihat Detail Event
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-300 shadow-sm">
                    <svg class="w-12 h-12 text-navy/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-navy/40 font-black uppercase tracking-widest text-sm">Belum ada agenda event yang dijadwalkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
