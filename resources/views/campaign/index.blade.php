@extends('layouts.main')
@section('title', 'Campaign & Donasi - TRIATLON LAMPUNG')

@section('content')
    <section class="bg-navy py-12 md:py-16 px-4 sm:px-8 md:px-16 text-center">
        <h1 class="font-oswald text-white text-3xl md:text-5xl font-bold uppercase tracking-wide mb-3">Campaign & Kolaborasi
        </h1>
        <p class="text-white/60 text-sm font-semibold max-w-xl mx-auto">Dukung ekosistem triatlon Lampung lewat donasi,
            kerjasama, atau ikuti campaign yang sedang berjalan.</p>
    </section>

    <section class="bg-cream py-10 md:py-16 px-4 sm:px-8 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach (['' => 'Semua', 'Donasi' => 'Donasi', 'Kerjasama' => 'Kerjasama', 'Campaign' => 'Campaign'] as $val => $label)
                        <a href="{{ route('campaign.index', $val ? ['tipe' => $val] : []) }}"
                            class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-colors {{ $tipe == $val ? 'bg-navy text-yellow' : 'bg-white text-navy border border-gray-300 hover:border-navy' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                @if (auth()->check() && auth()->user()->email === 'admin@triatlon.test')
                    <a href="{{ route('campaign.create') }}"
                        class="inline-flex items-center gap-2 bg-navy text-yellow px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-md shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Campaign
                    </a>
                @endif
            </div>

            <!-- Tab Filter -->
            <div class="flex flex-wrap justify-center gap-2 mb-10">
                @foreach (['' => 'Semua', 'Donasi' => 'Donasi', 'Kerjasama' => 'Kerjasama', 'Campaign' => 'Campaign'] as $val => $label)
                    <a href="{{ route('campaign.index', $val ? ['tipe' => $val] : []) }}"
                        class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-colors {{ $tipe == $val ? 'bg-navy text-yellow' : 'bg-white text-navy border border-gray-300 hover:border-navy' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($campaigns as $c)
                    <a href="{{ route('campaign.show', $c->slug) }}"
                        class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all overflow-hidden flex flex-col">
                        <div class="h-40 relative bg-navy overflow-hidden">
                            @if ($c->poster)
                                <img src="{{ asset($c->poster) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif
                            <span
                                class="absolute top-3 left-3 bg-yellow text-navy text-[9px] font-black uppercase px-2.5 py-1 rounded">{{ $c->tipe }}</span>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-black text-navy uppercase text-sm leading-tight mb-2 line-clamp-2">
                                    {{ $c->judul }}</h3>
                                <p class="text-xs text-navy/60 font-semibold line-clamp-2 mb-4">
                                    {{ Str::limit($c->deskripsi, 80) }}</p>
                            </div>

                            @if ($c->tipe === 'Donasi')
                                <div>
                                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-1.5">
                                        <div class="h-full bg-yellow-400" style="width: {{ $c->persen_tercapai }}%"></div>
                                    </div>
                                    <p class="text-[10px] font-black text-navy/70">Rp
                                        {{ number_format($c->dana_terkumpul, 0, ',', '.') }} <span
                                            class="text-navy/40 font-bold">terkumpul</span></p>
                                </div>
                            @else
                                <span
                                    class="text-[10px] font-black text-navy uppercase tracking-wide inline-flex items-center gap-1">
                                    Lihat Detail
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3">
                                        </path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full border-2 border-dashed border-gray-300 rounded-2xl p-10 text-center">
                        <p class="font-black text-navy/40 uppercase tracking-widest text-sm">Belum ada campaign aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
