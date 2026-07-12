@extends('layouts.main')

@section('title', ($title ?? 'Segera Hadir') . ' - FTI LAMPUNG')

@section('content')
    <section class="bg-[#0B1528] min-h-[70vh] flex items-center justify-center px-8 md:px-16 py-20">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-20 h-20 bg-yellow/10 border border-yellow/30 rounded-2xl flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <span class="text-yellow text-xs font-black tracking-widest uppercase mb-3 block">Dalam Pengembangan</span>
            <h1 class="font-oswald text-white text-4xl md:text-5xl font-bold uppercase tracking-wide mb-6">
                {{ $title ?? 'Segera Hadir' }}
            </h1>
            <div class="w-16 h-1 bg-yellow mx-auto mb-6"></div>

            <p class="text-sm md:text-base font-semibold text-white/60 leading-relaxed mb-10 max-w-md mx-auto">
                {{ $deskripsi ?? 'Fitur ini sedang kami kembangkan dan akan segera hadir. Terima kasih atas kesabarannya.' }}
            </p>

            <a href="{{ url('/') }}" class="inline-flex items-center bg-yellow text-navy px-8 py-3.5 font-black text-xs uppercase tracking-wider rounded-xl hover:bg-white transition-colors shadow-lg">
                Kembali ke Beranda
            </a>
        </div>
    </section>
@endsection
