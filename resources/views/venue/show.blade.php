@extends('layouts.main')
@section('title', $venue->nama . ' - FTI LAMPUNG')

@section('content')
    <!-- HEADER -->
    <section class="bg-navy py-12 px-6 md:px-16 border-b-4 border-yellow relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]"></div>
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-end gap-6 relative z-10">
            <div>
                <a href="{{ route('venue.index') }}"
                    class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Katalog
                </a>

                <div class="flex flex-wrap gap-2 mb-4">
                    <span
                        class="bg-yellow text-navy px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest shadow-sm">TINGKAT
                        {{ $venue->tingkat ?? 'LOKAL' }}</span>
                    <span
                        class="bg-blue-600 text-white px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                        </svg>
                        {{ $venue->daerah }}
                    </span>
                </div>

                <h1 class="font-oswald text-4xl md:text-6xl font-bold uppercase text-white leading-tight mb-2">
                    {{ $venue->nama }}</h1>
                <p class="text-white/70 font-semibold text-sm max-w-2xl leading-relaxed">{{ $venue->alamat }}</p>
            </div>

            @if (auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('venue.edit', $venue->id) }}"
                    class="bg-white text-navy px-8 py-3.5 rounded-xl font-black text-xs uppercase hover:bg-yellow transition-colors shadow-xl shrink-0">
                    Edit Data Venue
                </a>
            @endif
        </div>
    </section>

    <section class="bg-[#F4F6F9] py-12 px-6 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <!-- KOLOM KIRI: Galeri & Deskripsi -->
            <div class="lg:w-7/12 space-y-8">
                <!-- Galeri Interaktif -->
                <div class="bg-white p-3 rounded-[2rem] border border-gray-200 shadow-sm">
                    @if ($venue->photos->count() > 0)
                        <!-- Foto Utama -->
                        <div class="rounded-2xl overflow-hidden mb-3 relative bg-gray-900 group">
                            <img id="main-image" src="{{ asset($venue->photos->first()->path_foto) }}"
                                class="w-full h-80 md:h-[450px] object-cover transition-opacity duration-300">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                                <span
                                    class="text-white font-black uppercase tracking-widest text-xs border border-white/30 px-3 py-1 rounded backdrop-blur">Galeri
                                    Foto</span>
                            </div>
                        </div>
                        <!-- Thumbnail -->
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                            @foreach ($venue->photos as $foto)
                                <div class="aspect-square bg-gray-200 rounded-xl overflow-hidden cursor-pointer border-2 border-transparent hover:border-navy transition-all"
                                    onclick="changeMainImage('{{ asset($foto->path_foto) }}')">
                                    <img src="{{ asset($foto->path_foto) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="w-full h-80 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="font-black uppercase tracking-widest text-sm">Tidak Ada Foto Venue</p>
                        </div>
                    @endif
                </div>

                <!-- Deskripsi -->
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm">
                    <h3 class="font-black text-navy uppercase text-xl flex items-center gap-3 mb-6">
                        <svg class="w-6 h-6 text-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tentang Venue
                    </h3>
                    <p class="text-sm font-medium text-navy/70 leading-loose text-justify whitespace-pre-line">
                        {{ $venue->deskripsi ?: 'Tidak ada deskripsi yang ditambahkan untuk venue ini.' }}</p>
                </div>
            </div>

            <!-- KOLOM KANAN: Rute & Fasilitas -->
            <div class="lg:w-5/12 space-y-6">

                <!-- Rute Lintasan (Desain Card Warna) -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-200">
                    <h3 class="font-black text-navy uppercase text-xl mb-6">Informasi Rute Lintasan</h3>
                    <div class="space-y-4">

                        <!-- Swim -->
                        <div class="bg-[#EBF5FF] border border-[#BDE0FE] p-4 rounded-2xl flex gap-4 items-start">
                            <div class="bg-blue-500 text-white p-3 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M2 18c.6.5 1.2 1 2.5 1s1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1 1.2 1 2.5 1 1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1l-.9 1.6c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1L2 18zm14.5-4.6c1.1-.6 1.8-1.7 1.8-3 0-1.9-1.6-3.4-3.5-3.4-1 0-1.9.4-2.5 1.1L11.1 9l1.2 1.2-3.5 3.5-2-2c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4l2.7 2.7c.4.4 1 .4 1.4 0l4.2-4.2.7.7c.4.4 1 .4 1.4 0 .1-.1.2-.2.2-.3zm-3.7-5c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Rute Renang
                                    (Swim)</p>
                                <p class="text-sm font-bold text-navy">{{ $venue->rute_renang ?: 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <!-- Bike -->
                        <div class="bg-[#FEF9C3] border border-[#FDE047] p-4 rounded-2xl flex gap-4 items-start">
                            <div class="bg-yellow-500 text-white p-3 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M15.5 5.5c.83 0 1.5-.67 1.5-1.5S16.33 2.5 15.5 2.5 14 3.17 14 4s.67 1.5 1.5 1.5zM5 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5S3.62 13.5 5 13.5 7.5 14.62 7.5 16 6.38 18.5 5 18.5zm5.8-10l2.4-2.4.8.8C15.1 8.1 16.5 8.9 18 8.9V7.1c-1.1 0-2.1-.6-2.6-1.5l-1.7-2.4c-.4-.5-1-.8-1.6-.8-.5 0-1 .2-1.3.5L7.6 6c-.5.5-.8 1.1-.8 1.8 0 .7.3 1.3.8 1.8L11 13v5h2v-6.2l-2.2-2.3zM19 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-yellow-700 uppercase tracking-widest mb-1">Rute Sepeda
                                    (Bike)</p>
                                <p class="text-sm font-bold text-navy">{{ $venue->rute_sepeda ?: 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <!-- Run -->
                        <div class="bg-[#FEE2E2] border border-[#FCA5A5] p-4 rounded-2xl flex gap-4 items-start">
                            <div class="bg-red-500 text-white p-3 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M13.5 5.5c.83 0 1.5-.67 1.5-1.5S14.33 2.5 13.5 2.5 12 3.17 12 4s.67 1.5 1.5 1.5zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 7.4V11h2V8.7l1.8-.8z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Rute Lari
                                    (Run)</p>
                                <p class="text-sm font-bold text-navy">{{ $venue->rute_lari ?: 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <!-- Transition (tidak diubah, ikon panah transisi sudah cukup representatif) -->
                        <div class="bg-gray-100 border border-gray-300 p-4 rounded-2xl flex gap-4 items-start mt-2">
                            <div class="bg-gray-600 text-white p-3 rounded-xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-1">Area Transisi
                                    (T1/T2)</p>
                                <p class="text-sm font-bold text-navy">{{ $venue->area_transisi ?: 'Belum diisi' }}</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Fasilitas Grid -->
                <div class="bg-cream p-8 rounded-3xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-navy uppercase text-xl flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 6a2 2 0 012-2h12a2 2 0 012 2M4 6v12a2 2 0 002 2h12a2 2 0 002-2V6m-9 5h4">
                                </path>
                            </svg>
                            Fasilitas Tersedia
                        </h3>
                        @php $fasilitasArray = is_array($venue->fasilitas) ? $venue->fasilitas : json_decode($venue->fasilitas, true); @endphp
                        @if (!empty($fasilitasArray) && count($fasilitasArray) > 0)
                            <span
                                class="bg-navy text-yellow text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full shrink-0">{{ count($fasilitasArray) }}
                                Item</span>
                        @endif
                    </div>

                    @if (!empty($fasilitasArray) && count($fasilitasArray) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $accents = [
                                    ['bg' => 'bg-navy/5', 'icon' => 'text-navy', 'ring' => 'hover:border-navy/30'],
                                    [
                                        'bg' => 'bg-yellow/15',
                                        'icon' => 'text-yellow-700',
                                        'ring' => 'hover:border-yellow-400',
                                    ],
                                    [
                                        'bg' => 'bg-teal-50',
                                        'icon' => 'text-teal-600',
                                        'ring' => 'hover:border-teal-300',
                                    ],
                                    [
                                        'bg' => 'bg-orange-50',
                                        'icon' => 'text-orange-600',
                                        'ring' => 'hover:border-orange-300',
                                    ],
                                ];

                                function fasilitasIcon($nama)
                                {
                                    $n = strtolower($nama);
                                    if (str_contains($n, 'wifi') || str_contains($n, 'internet')) {
                                        return 'M8.111 16.404a5.5 5.5 0 017.778 0M5.05 13.243a10 10 0 0113.9 0M2 9.9a15 15 0 0120 0M12 20h.01';
                                    }
                                    if (str_contains($n, 'parkir')) {
                                        return 'M9 17V7h4a3 3 0 010 6H9m10-6v10M5 21h14';
                                    }
                                    if (str_contains($n, 'toilet') || str_contains($n, 'kamar mandi')) {
                                        return 'M7 21V9a4 4 0 118 0v12M5 21h14M9 9h6';
                                    }
                                    if (
                                        str_contains($n, 'konsumsi') ||
                                        str_contains($n, 'makan') ||
                                        str_contains($n, 'snack')
                                    ) {
                                        return 'M12 3v18M8 3v6a4 4 0 008 0V3M8 3H6a2 2 0 00-2 2v2a2 2 0 002 2h2';
                                    }
                                    if (str_contains($n, 'ac') || str_contains($n, 'pendingin')) {
                                        return 'M12 2v20M4.93 6.93l14.14 10.14M19.07 6.93L4.93 17.07';
                                    }
                                    if (str_contains($n, 'sertifikat')) {
                                        return 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z';
                                    }
                                    if (
                                        str_contains($n, 'p3k') ||
                                        str_contains($n, 'medis') ||
                                        str_contains($n, 'kesehatan')
                                    ) {
                                        return 'M12 8v8m-4-4h8m5 0a9 9 0 11-18 0 9 9 0 0118 0z';
                                    }
                                    if (str_contains($n, 'kamera') || str_contains($n, 'cctv')) {
                                        return 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
                                    }
                                    if (
                                        str_contains($n, 'musholla') ||
                                        str_contains($n, 'mushola') ||
                                        str_contains($n, 'ibadah')
                                    ) {
                                        return 'M12 3v3m0 0a4 4 0 014 4c0 2.5-4 8-4 8s-4-5.5-4-8a4 4 0 014-4z';
                                    }
                                    return 'M13 10V3L4 14h7v7l9-11h-7z';
                                }
                            @endphp

                            @foreach ($fasilitasArray as $i => $fas)
                                @php $accent = $accents[$i % count($accents)]; @endphp
                                <div
                                    class="flex items-center gap-3 bg-white p-3.5 rounded-2xl border border-gray-200 shadow-sm {{ $accent['ring'] }} hover:shadow-md transition-all">
                                    <div
                                        class="w-10 h-10 shrink-0 rounded-xl {{ $accent['bg'] }} flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $accent['icon'] }}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ fasilitasIcon($fas) }}"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-navy leading-tight">{{ $fas }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="flex flex-col items-center justify-center gap-2 bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 13h6m-3-3v6m-9 4h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="text-xs font-bold text-gray-400">Fasilitas belum dicantumkan.</p>
                        </div>
                    @endif
                </div>

                <!-- Maps Button -->
                @if ($venue->link_maps)
                    <a href="{{ $venue->link_maps }}" target="_blank"
                        class="flex items-center justify-center gap-2 w-full bg-navy text-yellow py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-yellow hover:text-navy transition-colors shadow-lg transform hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Buka Rute via Google Maps
                    </a>
                @endif

            </div>
        </div>
    </section>

    <script>
        function changeMainImage(url) {
            const mainImg = document.getElementById('main-image');
            mainImg.style.opacity = 0;
            setTimeout(() => {
                mainImg.src = url;
                mainImg.style.opacity = 1;
            }, 200); // Sinkron dengan durasi CSS transition (300ms)
        }
    </script>
@endsection
