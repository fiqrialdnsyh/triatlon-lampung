@extends('layouts.main')

@section('title', 'Pengurus Cabang ' . $namaDaerah . ' - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-6 md:px-16 min-h-screen relative">
        <div class="max-w-5xl mx-auto">

            <a href="{{ url('/pengurus') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-8">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE STRUKTUR PROVINSI
            </a>

            <div class="bg-cream rounded-[2rem] overflow-hidden shadow-2xl relative">
                <!-- Header Banner -->
                <div class="bg-navy border-b-4 border-yellow p-10 md:p-14 text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="text-yellow text-xs font-black tracking-widest uppercase mb-3 block">Struktur Kepengurusan Daerah</span>
                        <h1 class="font-oswald text-3xl md:text-5xl font-bold uppercase text-white tracking-wide mb-4">
                            PENGKAB/PENGKOT FTI<br>{{ $namaDaerah }}
                        </h1>

                        @if(strtolower($infoDaerah->status_cabang) == 'aktif')
                            <span class="bg-green-500 text-white text-xs font-black uppercase px-4 py-1.5 rounded-full tracking-wider shadow-lg">STATUS: AKTIF</span>
                        @else
                            <span class="bg-yellow-500 text-navy text-xs font-black uppercase px-4 py-1.5 rounded-full tracking-wider shadow-lg">STATUS: VAKUM</span>
                        @endif
                    </div>
                </div>

                <!-- Konten Struktur -->
                <div class="p-8 md:p-14">

                    <!-- KETUA CABANG -->
                    <div class="mb-16">
                        <h2 class="text-center text-navy/40 text-xs font-black uppercase tracking-widest mb-6">Pimpinan Cabang</h2>
                        @if($ketua)
                            <div class="flex justify-center">
                                <div class="bg-white border border-gray-200 p-8 rounded-2xl shadow-xl text-center w-full max-w-sm">
                                    <div class="w-20 h-20 mx-auto bg-navy text-yellow rounded-full mb-5 flex items-center justify-center shadow-inner">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest bg-yellow/10 inline-block px-3 py-1 rounded mb-3">{{ $ketua->jabatan }}</p>
                                    <h3 class="font-black text-navy text-xl uppercase leading-tight">{{ $ketua->nama }}</h3>
                                </div>
                            </div>
                        @else
                            <p class="text-center font-bold text-sm text-navy/50 italic">Posisi Ketua Cabang belum ditentukan.</p>
                        @endif
                    </div>

                    <!-- JAJARAN PENGURUS LAINNYA -->
                    <div>
                        <h2 class="text-center text-navy/40 text-xs font-black uppercase tracking-widest mb-6">Jajaran Pengurus Cabang</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @forelse($anggota as $p)
                                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow flex flex-col justify-center text-center transform hover:-translate-y-1 transition-transform">
                                    <h3 class="font-black text-navy text-sm uppercase mb-2 leading-tight">{{ $p->nama }}</h3>
                                    <div>
                                        <p class="text-[9px] font-bold text-navy/50 uppercase tracking-widest bg-gray-50 inline-block px-2 py-1 rounded">{{ $p->jabatan }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center border-2 border-dashed border-gray-200 rounded-2xl">
                                    <p class="text-xs font-bold text-navy/40 uppercase tracking-widest">Belum ada jajaran pengurus tambahan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
