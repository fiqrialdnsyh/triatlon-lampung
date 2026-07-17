@extends('layouts.main')

@section('title', 'Beranda - TRIATLON LAMPUNG')

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

@section('content')
    <section class="relative bg-center bg-cover h-[380px] sm:h-[440px] md:h-[500px] flex flex-col items-center justify-center text-center px-4"
        style="background-image: url('{{ asset('images/beranda.jpeg') }}');" data-aos="fade-in">
        <div class="absolute inset-0 bg-black bg-opacity-40 sm:bg-opacity-30"></div>
        <div class="relative z-10 px-2">
            <h1
                class="font-oswald text-white text-3xl sm:text-5xl md:text-7xl lg:text-8xl font-bold uppercase leading-[1.15] tracking-tight mb-6 md:mb-8 drop-shadow-lg">
                Next-Gen Triathlon!
            </h1>
            <a href="{{ url('/event') }}"
                class="inline-block bg-yellow text-navy px-5 md:px-6 py-2 md:py-2.5 font-black text-xs md:text-sm uppercase rounded-sm hover:bg-white transition duration-300">
                FIND YOUR NEXT RACE
            </a>
        </div>
    </section>

    <!-- INTEGRASI DATA EVENT TERBARU DARI DATABASE (MAIN EVENT) -->
    @php
        // Hanya event yang tanggal pelaksanaannya hari ini atau di masa depan (event lewat otomatis tidak tampil)
        $upcomingMainEvents = \App\Models\MainEvent::where('tanggal_pelaksanaan', '>=', now()->startOfDay())
                            ->orderBy('tanggal_pelaksanaan', 'asc')
                            ->take(6)
                            ->get();
    @endphp

    <section class="bg-cream py-12 md:py-20 px-4 sm:px-8 md:px-16" data-aos="fade-up">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8 md:mb-12">
                <span class="text-navy/40 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-2 block">Jangan Sampai Terlewat</span>
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl text-navy uppercase tracking-wide">EVENT YANG AKAN DATANG</h2>
                <div class="w-16 h-1 bg-yellow mx-auto mt-4"></div>
            </div>

            @if($upcomingMainEvents->count())
                <div class="relative" id="eventCarouselWrapper">
                    <div id="eventCarouselTrack" class="overflow-hidden rounded-[1.75rem] md:rounded-[2rem] shadow-2xl">
                        <div id="eventCarouselSlides" class="flex transition-transform duration-700 ease-out">
                            @foreach($upcomingMainEvents as $event)
                                @php
                                    $daysLeft = (int) floor(now()->diffInDays(\Carbon\Carbon::parse($event->tanggal_pelaksanaan), false));
                                @endphp
                                <a href="{{ route('main_event.show', $event->slug) }}" class="group block bg-navy shrink-0 w-full">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="relative h-56 sm:h-72 md:h-[380px] overflow-hidden">
                                            @if($event->poster)
                                                <img src="{{ asset($event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            @else
                                                <div class="w-full h-full bg-navy flex items-center justify-center">
                                                    <svg class="w-16 h-16 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-navy via-navy/20 to-transparent"></div>

                                            <div class="absolute top-5 left-5 flex flex-col gap-2">
                                                @if($event->is_open_active)
                                                    <span class="bg-yellow text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest w-fit shadow-md">Jalur Open</span>
                                                @endif
                                                @if($event->is_kejurnas_active)
                                                    <span class="bg-white text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest w-fit shadow-md">Jalur Kejurnas</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="p-6 sm:p-8 md:p-10 flex flex-col justify-center">
                                            <span class="text-yellow text-[10px] font-black uppercase tracking-widest mb-3">
                                                @if($daysLeft == 0) Hari Ini
                                                @elseif($daysLeft == 1) Besok
                                                @else {{ $daysLeft }} Hari Lagi
                                                @endif
                                            </span>
                                            <h3 class="font-oswald text-white text-2xl sm:text-3xl md:text-4xl font-bold uppercase leading-tight mb-4 group-hover:text-yellow transition-colors line-clamp-2">
                                                {{ $event->judul }}
                                            </h3>
                                            <div class="space-y-2 mb-6">
                                                <div class="flex items-center gap-2 text-white/70 text-xs font-bold">
                                                    <svg class="w-4 h-4 text-yellow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                                                </div>
                                                <div class="flex items-center gap-2 text-white/70 text-xs font-bold">
                                                    <svg class="w-4 h-4 text-yellow shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                    <span class="line-clamp-1">{{ $event->lokasi }}</span>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center gap-2 bg-yellow text-navy px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest w-fit group-hover:bg-white transition-colors shadow-lg">
                                                Lihat Detail Event
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($upcomingMainEvents->count() > 1)
                <script>
                    (function() {
                        const slides = document.getElementById('eventCarouselSlides');
                        const totalSlides = {{ $upcomingMainEvents->count() }};
                        let currentIndex = 0;

                        setInterval(function() {
                            currentIndex = (currentIndex + 1) % totalSlides;
                            slides.style.transform = `translateX(-${currentIndex * 100}%)`;
                        }, 5000);
                    })();
                </script>
                @endif
            @else
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 sm:p-10 text-center bg-white">
                    <svg class="w-10 h-10 text-navy/20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    <p class="font-black text-navy/40 uppercase tracking-widest text-xs sm:text-sm">Saat ini belum ada jadwal event yang diterbitkan.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- FITUR PORTAL -->

    <section class="bg-navy py-12 md:py-20 px-4 sm:px-8 md:px-16" data-aos="fade-up">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8 md:mb-12">
                <span class="text-yellow/60 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-2 block">Jelajahi Ekosistem Triatlon</span>
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl text-white uppercase tracking-wide">FITUR PORTAL</h2>
                <div class="w-16 h-1 bg-yellow mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-10">

                <a href="{{ url('/personil') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">ATLET, PELATIH<br>& WASIT</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Direktori data profil, rekam jejak, dan progres performa atlet, pelatih, serta wasit terdaftar.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center transition-colors group-hover:bg-navy group-hover:text-yellow">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <!-- Card Pengurus -->
                <a href="{{ url('/pengurus') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">PENGURUS</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Susunan kepengurusan FTI tingkat Provinsi hingga Pengurus Cabang di Kabupaten/Kota.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ url('/berita') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v5a2 2 0 01-2 2h-1m-1 4a2 2 0 01-2 2h-2m-2-10a1 1 0 00-1-1H7a1 1 0 00-1 1v1h6V8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">BERITA</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Pembaruan terkini, pengumuman, dan liputan khusus dari kejuaraan serta pelatihan.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ url('/venue') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">VENUE</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Informasi detail lokasi perlombaan, fasilitas transisi, dan rute lintasan renang, sepeda, lari.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

            </div>
        </div>
    </section>
@endsection
