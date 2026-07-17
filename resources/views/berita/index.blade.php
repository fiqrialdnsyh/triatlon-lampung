@extends('layouts.main')

@section('title', 'Berita & Pengumuman - FTI LAMPUNG')

@section('content')
    <!-- Header Section -->
    <section class="bg-navy py-16 px-8 md:px-16 text-center" data-aos="fade-down">
        <h1 class="font-oswald text-white text-4xl md:text-6xl font-bold uppercase tracking-wide mb-4">BERITA TERKINI</h1>
        <p class="text-white/70 font-semibold max-w-2xl mx-auto text-sm md:text-base">Informasi terbaru seputar kejuaraan, program pembinaan, dan perkembangan olahraga Triatlon di Provinsi Lampung.</p>
    </section>

    <!-- News Grid Section -->
    <section class="bg-navy pb-24 px-8 md:px-16" data-aos="fade-up">

        <!-- Pembungkus Judul Bagian & Tombol Tambah Admin -->
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-white/10 pb-4">
            <h2 class="text-white text-lg font-black uppercase tracking-wider mb-4 md:mb-0">Daftar Berita & Artikel</h2>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="flex gap-2">
                        <a href="{{ url('/berita/create') }}" class="bg-yellow text-navy px-5 py-2.5 font-black text-xs uppercase rounded-sm hover:bg-white transition-colors shadow-lg">
                            + TULIS BERITA BARU
                        </a>
                        <a href="{{ route('berita.kelola') }}" class="bg-white text-navy px-5 py-2.5 font-black text-xs uppercase rounded-sm hover:bg-gray-200 transition-colors shadow-lg">
                            KELOLA BERITA
                        </a>
                    </div>
                @endif
            @endauth
        </div>

        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @if(session('success'))
                <div class="col-span-full mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($beritas as $item)
                <div class="bg-cream rounded-[2rem] overflow-hidden shadow-lg transform hover:-translate-y-2 transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset($item->foto_cover) }}" alt="Cover Berita" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-4 left-4 bg-yellow text-navy text-[10px] font-black px-3 py-1.5 rounded-sm uppercase tracking-wider shadow-md">
                            {{ $item->kategori }}
                        </div>
                    </div>
                    <div class="p-8 flex flex-col flex-grow">
                        <p class="text-xs font-bold text-navy/50 mb-2 uppercase tracking-wide">{{ $item->created_at->translatedFormat('d F Y') }}</p>
                        <h3 class="font-oswald text-xl font-bold uppercase text-navy leading-snug mb-3">{{ $item->judul }}</h3>
                        <p class="text-sm font-semibold text-navy/70 mb-6 line-clamp-3">
                            <!-- strip_tags berguna untuk menghilangkan kode HTML dari Trix Editor saat ditampilkan sebagai ringkasan -->
                            {{ Str::limit(strip_tags($item->konten), 120) }}
                        </p>
                        <div class="mt-auto pt-4 border-t border-navy/10">
                            <a href="{{ url('/berita/' . $item->id) }}" class="inline-flex items-center text-xs font-black text-navy uppercase hover:text-yellow transition-colors">
                                BACA SELENGKAPNYA
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white/5 border border-white/10 rounded-2xl">
                    <p class="text-white/60 font-bold uppercase tracking-wider">Belum ada berita yang dipublikasikan.</p>
                </div>
            @endforelse

        </div>
    </section>
@endsection
