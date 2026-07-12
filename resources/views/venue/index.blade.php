@extends('layouts.main')
@section('title', 'Katalog Venue - FTI LAMPUNG')

@section('content')
@php
    $kabupatenLampung = [
        'Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah',
        'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pesisir Barat',
        'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'
    ];
@endphp

<!-- HERO SECTION -->
<section class="bg-navy pt-20 pb-24 px-6 md:px-16 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="relative z-10 max-w-3xl mx-auto">
        <span class="text-yellow font-black tracking-widest text-[10px] uppercase border border-yellow px-3 py-1 rounded-full mb-4 inline-block">Fasilitas & Rute Triatlon</span>
        <h1 class="font-oswald text-white text-4xl md:text-6xl font-bold uppercase tracking-wide mb-4">DIRECTORY VENUE</h1>
        <p class="text-white/70 font-medium text-sm leading-relaxed">Jelajahi pusat data arena perlombaan, area transisi, dan rute lintasan olahraga Triatlon terbaik di seluruh penjuru Provinsi Lampung.</p>

        @if(auth()->check() && auth()->user()->email == 'admin@triatlon.test')
            <div class="mt-8">
                <a href="{{ route('venue.create') }}" class="inline-block bg-yellow text-navy px-8 py-3.5 rounded-xl font-black text-xs uppercase tracking-wider hover:bg-white transition-colors shadow-xl hover:-translate-y-1 transform duration-200">
                    + Tambah Venue Baru
                </a>
            </div>
        @endif
    </div>
</section>

<section class="bg-[#F8F9FA] pb-20 px-6 md:px-16 min-h-screen relative">
    <div class="max-w-7xl mx-auto">

        <!-- FLOATING FILTER BAR -->
        <div class="bg-white rounded-2xl shadow-xl p-3 border border-gray-100 max-w-4xl mx-auto -mt-10 relative z-20 flex flex-col md:flex-row items-center gap-3">
            <div class="flex items-center justify-center bg-navy/5 text-navy p-4 rounded-xl shrink-0 hidden md:flex">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </div>
            <form action="{{ route('venue.index') }}" method="GET" class="w-full flex flex-col md:flex-row items-center gap-3">
                <div class="w-full relative">
                    <select name="daerah" class="appearance-none bg-gray-50 border border-gray-200 text-navy text-sm font-black w-full px-5 py-4 rounded-xl focus:ring-2 focus:ring-yellow focus:border-yellow cursor-pointer uppercase tracking-wide" onchange="this.form.submit()">
                        <option value="">-- SEMUA KABUPATEN / KOTA --</option>
                        @foreach($kabupatenLampung as $kab)
                            <option value="{{ strtoupper($kab) }}" {{ request('daerah') == strtoupper($kab) ? 'selected' : '' }}>
                                {{ strtoupper($kab) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-navy">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto bg-navy text-white hover:bg-yellow hover:text-navy px-8 py-4 rounded-xl font-black text-xs uppercase tracking-widest transition-colors shadow-md">
                    Filter
                </button>
            </form>
        </div>

        <!-- INFO TEXT -->
        <div class="text-center mb-10 mt-8">
            <p class="text-sm font-black text-navy/40 uppercase tracking-widest">Menampilkan {{ $venues->count() }} Lokasi Tersedia</p>
        </div>

        <!-- GRID CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($venues as $v)
                <a href="{{ route('venue.show', $v->slug) }}" class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col overflow-hidden hover:-translate-y-2">

                    <!-- CARD IMAGE -->
                    <div class="h-56 bg-gray-200 relative overflow-hidden">
                        @if($v->photos->count() > 0)
                            <img src="{{ asset($v->photos->first()->path_foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-xs uppercase bg-gray-100">Tanpa Foto</div>
                        @endif

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="bg-yellow text-navy px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-md">
                                TINGKAT {{ $v->tingkat ?? 'LOKAL' }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-navy/80 backdrop-blur text-white text-[10px] font-black px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $v->photos->count() }}
                        </div>
                    </div>

                    <!-- CARD BODY -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1.5 text-blue-600 font-bold text-[10px] uppercase tracking-widest mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $v->daerah }}
                            </div>
                            <h3 class="font-black text-navy uppercase text-xl leading-tight mb-3 group-hover:text-blue-600 transition-colors">{{ $v->nama }}</h3>
                            <p class="text-xs font-medium text-navy/60 line-clamp-2 leading-relaxed mb-4">{{ $v->alamat }}</p>
                        </div>

                        <!-- Rute Preview -->
                        <div class="border-t border-gray-100 pt-4 flex gap-4 text-xs font-bold text-navy/50">
                            <div class="flex items-center gap-1" title="Rute Renang">
                                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 18c.6.5 1.2 1 2.5 1s1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1 1.2 1 2.5 1 1.9-.5 2.5-1 1.2-1 2.5-1 1.9.5 2.5 1l-.9 1.6c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1c-.5-.4-1-.8-1.9-.8s-1.4.4-1.9.8c-.6.5-1.4 1.1-2.7 1.1s-2.1-.6-2.7-1.1L2 18zm14.5-4.6c1.1-.6 1.8-1.7 1.8-3 0-1.9-1.6-3.4-3.5-3.4-1 0-1.9.4-2.5 1.1L11.1 9l1.2 1.2-3.5 3.5-2-2c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4l2.7 2.7c.4.4 1 .4 1.4 0l4.2-4.2.7.7c.4.4 1 .4 1.4 0 .1-.1.2-.2.2-.3zm-3.7-5c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1z"/>
                                </svg>
                                {{ $v->rute_renang ? 'Tersedia' : '-' }}
                            </div>
                            <div class="flex items-center gap-1" title="Rute Sepeda">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.5 5.5c.83 0 1.5-.67 1.5-1.5S16.33 2.5 15.5 2.5 14 3.17 14 4s.67 1.5 1.5 1.5zM5 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5S3.62 13.5 5 13.5 7.5 14.62 7.5 16 6.38 18.5 5 18.5zm5.8-10l2.4-2.4.8.8C15.1 8.1 16.5 8.9 18 8.9V7.1c-1.1 0-2.1-.6-2.6-1.5l-1.7-2.4c-.4-.5-1-.8-1.6-.8-.5 0-1 .2-1.3.5L7.6 6c-.5.5-.8 1.1-.8 1.8 0 .7.3 1.3.8 1.8L11 13v5h2v-6.2l-2.2-2.3zM19 12c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zm0 6.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                {{ $v->rute_sepeda ? 'Tersedia' : '-' }}
                            </div>
                            <div class="flex items-center gap-1" title="Rute Lari">
                                <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13.5 5.5c.83 0 1.5-.67 1.5-1.5S14.33 2.5 13.5 2.5 12 3.17 12 4s.67 1.5 1.5 1.5zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 7.4V11h2V8.7l1.8-.8z"/>
                                </svg>
                                {{ $v->rute_lari ? 'Tersedia' : '-' }}
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-24 flex flex-col items-center justify-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="text-navy/50 font-black uppercase tracking-widest text-sm">Tidak ada venue ditemukan untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
