@extends('layouts.main')

@section('title', 'Triathlon Lampung (FTI Lampung) - Portal Resmi Atlet, Event & Ranking')

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

@section('content')
    <section
        class="relative bg-center bg-cover h-[380px] sm:h-[440px] md:h-[500px] flex flex-col items-center justify-center text-center px-4"
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

    <section class="bg-white py-16 md:py-24 px-6 md:px-16 relative overflow-hidden" data-aos="fade-up">
        <!-- Ornamen Latar Belakang -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-yellow/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-navy/5 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- KOLOM KIRI: Judul & Intro -->
                <div class="lg:col-span-5 relative">
                    <!-- Garis Aksen -->
                    <div class="absolute -left-5 md:-left-8 top-2 w-1.5 h-20 bg-yellow rounded-full hidden md:block"></div>

                    <span class="text-yellow-600 text-xs font-black tracking-widest uppercase mb-3 block">FTI
                        Lampung</span>
                    <h2
                        class="font-oswald text-3xl md:text-4xl lg:text-5xl font-bold text-navy uppercase tracking-wide mb-6 leading-tight">
                        Membangun Prestasi<br>Triathlon Lampung
                    </h2>
                    <p class="text-sm md:text-base font-bold text-navy/80 leading-relaxed mb-8">
                        Federasi Triathlon Indonesia (FTI) Provinsi Lampung adalah induk organisasi resmi cabang olahraga
                        triathlon, menaungi pengurus cabang dari Bandar Lampung hingga Pesawaran.
                    </p>

                    <!-- Badge Ikon Olahraga -->
                    <div class="flex flex-wrap gap-3">

                        <!-- Renang -->
                        <div
                            class="bg-[#EBF5FF] border border-[#BDE0FE] pl-3 pr-4 py-2.5 rounded-xl flex items-center gap-3">
                            <div class="bg-blue-500 text-white p-2 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M2 18c.6.5 1.2 1 2.5 1s1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1 1.2 1 2.5 1 1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1l-.9 1.6c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1L2 18zm14.5-4.6c1.1-.6 1.8-1.7 1.8-3 0-1.9-1.6-3.4-3.5-3.4-1 0-1.9.4-2.5 1.1L11.1 9l1.2 1.2-3.5 3.5-2-2c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4l2.7 2.7c.4.4 1 .4 1.4 0l4.2-4.2.7.7c.4.4 1 .4 1.4 0 .1-.1.2-.2.2-.3zm-3.7-5c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-wider">Renang</span>
                        </div>

                        <!-- Sepeda -->
                        <div
                            class="bg-[#FEF9C3] border border-[#FDE047] pl-3 pr-4 py-2.5 rounded-xl flex items-center gap-3">
                            <div class="bg-yellow-500 text-white p-2 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M15.5 5.5c.83 0 1.5-.67 1.5-1.5S16.33 2.5 15.5 2.5 14 3.17 14 4s.67 1.5 1.5 1.5zM5 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5S3.62 13.5 5 13.5 7.5 14.62 7.5 16 6.38 18.5 5 18.5zm5.8-10l2.4-2.4.8.8C15.1 8.1 16.5 8.9 18 8.9V7.1c-1.1 0-2.1-.6-2.6-1.5l-1.7-2.4c-.4-.5-1-.8-1.6-.8-.5 0-1 .2-1.3.5L7.6 6c-.5.5-.8 1.1-.8 1.8 0 .7.3 1.3.8 1.8L11 13v5h2v-6.2l-2.2-2.3zM19 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-yellow-700 uppercase tracking-wider">Sepeda</span>
                        </div>

                        <!-- Lari -->
                        <div
                            class="bg-[#FEE2E2] border border-[#FCA5A5] pl-3 pr-4 py-2.5 rounded-xl flex items-center gap-3">
                            <div class="bg-red-500 text-white p-2 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M13.5 5.5c.83 0 1.5-.67 1.5-1.5S14.33 2.5 13.5 2.5 12 3.17 12 4s.67 1.5 1.5 1.5zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 7.4V11h2V8.7l1.8-.8z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black text-red-600 uppercase tracking-wider">Lari</span>
                        </div>

                    </div>
                </div>

                <!-- KOLOM KANAN: Detail Teks & Highlight Event -->
                <div class="lg:col-span-7 space-y-6">
                    <p class="text-sm md:text-base font-medium text-navy/70 leading-relaxed text-justify">
                        Kami berperan aktif secara konsisten dalam membina atlet, pelatih, dan wasit untuk disiplin
                        triathlon, duathlon, serta aquathlon. Target utama kami adalah mempersiapkan jajaran atlet terbaik
                        Lampung untuk berlaga dan membawa pulang medali pada <strong class="text-navy font-black">Pekan
                            Olahraga Nasional (PON) 2028</strong>.
                    </p>

                    <!-- Kartu Highlight Krakatau Triathlon -->
                    <div
                        class="bg-navy p-6 md:p-8 rounded-[2rem] shadow-xl relative overflow-hidden transform hover:-translate-y-1 transition-transform duration-300 mt-8">
                        <!-- Ornamen BG Kartu -->
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-yellow/20 rounded-full blur-3xl"></div>

                        <div class="relative z-10 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                            <div class="bg-white/10 p-4 rounded-2xl shrink-0 border border-white/10">
                                <div class="text-center">
                                    <span class="block text-yellow font-black text-2xl leading-none">OCT</span>
                                    <span
                                        class="block text-white text-[10px] font-bold uppercase tracking-widest mt-1">2026</span>
                                </div>
                            </div>
                            <div>
                                <span
                                    class="bg-yellow text-navy text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-3 inline-block shadow-sm">Agenda
                                    Utama Nasional</span>
                                <h3 class="font-oswald text-2xl font-bold text-white uppercase tracking-wide mb-2">Krakatau
                                    Triathlon Seri</h3>
                                <p class="text-sm text-white/70 leading-relaxed font-medium">
                                    Seri kejuaraan nasional yang akan diikuti oleh atlet dari berbagai provinsi. Event ini
                                    didesain untuk mengembangkan potensi <em class="text-yellow">sport tourism</em> dengan
                                    memanfaatkan keindahan garis pantai Lampung Selatan hingga Pesawaran.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
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
                <span class="text-navy/40 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-2 block">Jangan
                    Sampai Terlewat</span>
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl text-navy uppercase tracking-wide">EVENT YANG AKAN
                    DATANG</h2>
                <div class="w-16 h-1 bg-yellow mx-auto mt-4"></div>
            </div>

            @if ($upcomingMainEvents->count())
                <div class="relative" id="eventCarouselWrapper">
                    <div id="eventCarouselTrack" class="overflow-hidden rounded-[1.75rem] md:rounded-[2rem] shadow-2xl">
                        <div id="eventCarouselSlides" class="flex transition-transform duration-700 ease-out">
                            @foreach ($upcomingMainEvents as $event)
                                @php
                                    $daysLeft = (int) floor(
                                        now()->diffInDays(\Carbon\Carbon::parse($event->tanggal_pelaksanaan), false),
                                    );
                                @endphp
                                <a href="{{ route('main_event.show', $event->slug) }}"
                                    class="group block bg-navy shrink-0 w-full">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <div class="relative h-56 sm:h-72 md:h-[380px] overflow-hidden">
                                            @if ($event->poster)
                                                <img src="{{ asset($event->poster) }}" alt="{{ $event->judul }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            @else
                                                <div class="w-full h-full bg-navy flex items-center justify-center">
                                                    <svg class="w-16 h-16 text-white/10" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-navy via-navy/20 to-transparent">
                                            </div>

                                            <div class="absolute top-5 left-5 flex flex-col gap-2">
                                                @if ($event->is_open_active)
                                                    <span
                                                        class="bg-yellow text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest w-fit shadow-md">Jalur
                                                        Open</span>
                                                @endif
                                                @if ($event->is_kejurnas_active)
                                                    <span
                                                        class="bg-white text-navy px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest w-fit shadow-md">Jalur
                                                        Kejurnas</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="p-6 sm:p-8 md:p-10 flex flex-col justify-center">
                                            <span class="text-yellow text-[10px] font-black uppercase tracking-widest mb-3">
                                                @if ($daysLeft == 0)
                                                    Hari Ini
                                                @elseif($daysLeft == 1)
                                                    Besok
                                                @else
                                                    {{ $daysLeft }} Hari Lagi
                                                @endif
                                            </span>
                                            <h3
                                                class="font-oswald text-white text-2xl sm:text-3xl md:text-4xl font-bold uppercase leading-tight mb-4 group-hover:text-yellow transition-colors line-clamp-2">
                                                {{ $event->judul }}
                                            </h3>
                                            <div class="space-y-2 mb-6">
                                                <div class="flex items-center gap-2 text-white/70 text-xs font-bold">
                                                    <svg class="w-4 h-4 text-yellow shrink-0" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                                                </div>
                                                <div class="flex items-center gap-2 text-white/70 text-xs font-bold">
                                                    <svg class="w-4 h-4 text-yellow shrink-0" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                    </svg>
                                                    <span class="line-clamp-1">{{ $event->lokasi }}</span>
                                                </div>
                                            </div>
                                            <span
                                                class="inline-flex items-center gap-2 bg-yellow text-navy px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest w-fit group-hover:bg-white transition-colors shadow-lg">
                                                Lihat Detail Event
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if ($upcomingMainEvents->count() > 1)
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
                    <svg class="w-10 h-10 text-navy/20 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="font-black text-navy/40 uppercase tracking-widest text-xs sm:text-sm">Saat ini belum ada
                        jadwal event yang diterbitkan.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- FITUR PORTAL -->

    <section class="bg-navy py-12 md:py-20 px-4 sm:px-8 md:px-16" data-aos="fade-up">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8 md:mb-12">
                <span
                    class="text-yellow/60 text-[10px] sm:text-xs font-black uppercase tracking-widest mb-2 block">Jelajahi
                    Ekosistem Triatlon</span>
                <h2 class="font-black text-2xl sm:text-3xl md:text-4xl text-white uppercase tracking-wide">FITUR PORTAL
                </h2>
                <div class="w-16 h-1 bg-yellow mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-10">

                <a href="{{ url('/personil') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3
                        class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">
                        ATLET, PELATIH<br>& WASIT</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Direktori data profil atlet pelatih dan wasit.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center transition-colors group-hover:bg-navy group-hover:text-yellow">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <!-- Card Pengurus -->
                <a href="{{ url('/pengurus') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3
                        class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">
                        PENGURUS</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Susunan kepengurusan Federasi Triathlon Lampung hingga pengurus cabang di Kabupaten/Kota.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ url('/berita') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v5a2 2 0 01-2 2h-1m-1 4a2 2 0 01-2 2h-2m-2-10a1 1 0 00-1-1H7a1 1 0 00-1 1v1h6V8z">
                            </path>
                        </svg>
                    </div>
                    <h3
                        class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">
                        BERITA</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Pembaruan terkini, pengumuman, dan liputan khusus dari kejuaraan serta pelatihan.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

                <a href="{{ url('/venue') }}"
                    class="feature-card bg-cream p-6 sm:p-8 md:p-10 rounded-[1.5rem] md:rounded-[2rem] relative flex flex-col items-start min-h-[220px] sm:min-h-[250px] md:min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 bg-yellow rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-navy" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                    <h3
                        class="text-xl sm:text-2xl md:text-3xl font-black uppercase leading-tight mb-2 md:mb-3 text-navy pr-14 md:pr-0">
                        VENUE</h3>
                    <p class="text-xs sm:text-sm font-semibold text-navy/70 leading-relaxed pr-10 md:pr-12">
                        Informasi detail lokasi perlombaan.
                    </p>
                    <div
                        class="absolute bottom-5 right-5 md:bottom-8 md:right-8 w-10 h-10 md:w-12 md:h-12 bg-yellow rounded-lg md:rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </a>

            </div>
        </div>
    </section>
@endsection
