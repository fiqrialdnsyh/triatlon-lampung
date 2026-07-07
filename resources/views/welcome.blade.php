@extends('layouts.main')

@section('title', 'Beranda - TRIATLON LAMPUNG')

@section('content')
    <section class="relative bg-center bg-cover h-[550px] flex flex-col items-center justify-center text-center"
        style="background-image: url('{{ asset('images/beranda.jpeg') }}');" data-aos="fade-in">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="relative z-10 px-4">
            <h1
                class="font-oswald text-white text-6xl md:text-8xl font-bold uppercase leading-[1.1] tracking-tight mb-8 drop-shadow-lg">
                PUSH YOUR LIMITS.<br>TRIUMPH EVERYWHERE.
            </h1>
            <button
                class="bg-yellow text-navy px-6 py-2.5 font-black text-sm uppercase rounded-sm hover:bg-white transition duration-300">
                FIND YOUR NEXT RACE
            </button>
        </div>
    </section>

    <section class="bg-navy py-20 px-8 md:px-16" data-aos="fade-up">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">

            <a href="{{ url('/personil') }}"
                class="feature-card bg-cream p-10 rounded-[2rem] relative flex flex-col items-start min-h-[280px] shadow-lg cursor-pointer block group hover:-translate-y-2 transition-transform duration-300">
                <div
                    class="w-14 h-14 bg-yellow rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black uppercase leading-tight mb-3 text-navy">ATLET, PELATIH<br>& WASIT</h3>
                <p class="text-sm font-semibold text-navy/70 leading-relaxed pr-12">
                    Direktori data profil, rekam jejak, dan progres performa atlet, pelatih, serta wasit terdaftar.
                </p>
                <div
                    class="absolute bottom-8 right-8 w-12 h-12 bg-yellow rounded-xl flex items-center justify-center transition-colors group-hover:bg-navy group-hover:text-yellow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>

            <!-- Card Pengurus -->
            <a href="{{ url('/pengurus') }}"
                class="feature-card bg-cream p-10 rounded-[2rem] relative flex flex-col items-start min-h-[280px] shadow-lg cursor-pointer block group">
                <div
                    class="w-14 h-14 bg-yellow rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black uppercase leading-tight mb-3 text-navy">PENGURUS</h3>
                <p class="text-sm font-semibold text-navy/70 leading-relaxed pr-12">
                    Susunan kepengurusan FTI tingkat Provinsi hingga Pengurus Cabang di Kabupaten/Kota.
                </p>
                <div
                    class="absolute bottom-8 right-8 w-12 h-12 bg-yellow rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>

            <a href="{{ url('/berita') }}"
                class="feature-card bg-cream p-10 rounded-[2rem] relative flex flex-col items-start min-h-[280px] shadow-lg cursor-pointer block group">
                <div
                    class="w-14 h-14 bg-yellow rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v5a2 2 0 01-2 2h-1m-1 4a2 2 0 01-2 2h-2m-2-10a1 1 0 00-1-1H7a1 1 0 00-1 1v1h6V8z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black uppercase leading-tight mb-3 text-navy">BERITA</h3>
                <p class="text-sm font-semibold text-navy/70 leading-relaxed pr-12">
                    Pembaruan terkini, pengumuman, dan liputan khusus dari kejuaraan serta pelatihan.
                </p>
                <div
                    class="absolute bottom-8 right-8 w-12 h-12 bg-yellow rounded-xl flex items-center justify-center group-hover:bg-navy group-hover:text-yellow transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </a>

            <div
                class="feature-card bg-cream p-10 rounded-[2rem] relative flex flex-col items-start min-h-[280px] shadow-lg">
                <div class="w-14 h-14 bg-yellow rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black uppercase leading-tight mb-3 text-navy">VENUE</h3>
                <p class="text-sm font-semibold text-navy/70 leading-relaxed pr-12">
                    Informasi detail lokasi perlombaan, fasilitas transisi, dan rute lintasan renang, sepeda, lari.
                </p>
                <div
                    class="absolute bottom-8 right-8 w-12 h-12 bg-yellow rounded-xl flex items-center justify-center cursor-pointer hover:bg-navy hover:text-yellow transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-cream py-20 px-8 md:px-16" data-aos="fade-up">
        <h2 class="text-center font-black text-3xl md:text-4xl text-navy uppercase mb-12 tracking-wide">EVENT YANG AKAN
            DATANG</h2>
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="event-card border border-gray-300 rounded-2xl p-4 flex gap-5 bg-transparent items-center">
                <div class="relative flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1530549387789-4c1017266635?q=80&w=200&h=140&fit=crop"
                        alt="Swim" class="w-40 h-28 object-cover rounded-xl shadow-sm">
                    <div
                        class="absolute top-1 left-1 flex flex-col w-10 shadow-md overflow-hidden rounded-md border border-navy/10">
                        <div class="bg-navy text-white text-[10px] font-bold text-center py-0.5 uppercase">JUN</div>
                        <div class="bg-white text-navy text-base font-black text-center py-0.5">15</div>
                    </div>
                </div>
                <div class="flex flex-col justify-center pr-2">
                    <h4 class="font-black text-navy uppercase text-sm md:text-base leading-tight">IRONMAN 70.3<br>MIAMI, FL
                    </h4>
                    <p class="text-xs font-semibold text-navy/70 mt-1.5 mb-3 leading-snug">Kompetisi renang perairan
                        terbuka dengan standar rute internasional.</p>
                    <div>
                        <button
                            class="bg-yellow text-navy px-4 py-1.5 text-xs font-black uppercase rounded hover:bg-navy hover:text-yellow transition">REGISTER</button>
                    </div>
                </div>
            </div>

            <div class="event-card border border-gray-300 rounded-2xl p-4 flex gap-5 bg-transparent items-center">
                <div class="relative flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1502224562085-639556652f33?q=80&w=200&h=140&fit=crop"
                        alt="Run" class="w-40 h-28 object-cover rounded-xl shadow-sm">
                    <div
                        class="absolute top-1 left-1 flex flex-col w-10 shadow-md overflow-hidden rounded-md border border-navy/10">
                        <div class="bg-navy text-white text-[10px] font-bold text-center py-0.5 uppercase">JUN</div>
                        <div class="bg-white text-navy text-base font-black text-center py-0.5">15</div>
                    </div>
                </div>
                <div class="flex flex-col justify-center pr-2">
                    <h4 class="font-black text-navy uppercase text-sm md:text-base leading-tight">IRONMAN 70.3<br>MIAMI, FL
                    </h4>
                    <p class="text-xs font-semibold text-navy/70 mt-1.5 mb-3 leading-snug">Uji ketahanan fisik lari
                        marathon melintasi kondisi medan pesisir.</p>
                    <div>
                        <button
                            class="bg-yellow text-navy px-4 py-1.5 text-xs font-black uppercase rounded hover:bg-navy hover:text-yellow transition">REGISTER</button>
                    </div>
                </div>
            </div>

            <div class="event-card border border-gray-300 rounded-2xl p-4 flex gap-5 bg-transparent items-center">
                <div class="relative flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1519802772250-a52a9af0eacb?q=80&w=200&h=140&fit=crop"
                        alt="Swim" class="w-40 h-28 object-cover rounded-xl shadow-sm">
                    <div
                        class="absolute top-1 left-1 flex flex-col w-10 shadow-md overflow-hidden rounded-md border border-navy/10">
                        <div class="bg-navy text-white text-[10px] font-bold text-center py-0.5 uppercase">JUN</div>
                        <div class="bg-white text-navy text-base font-black text-center py-0.5">26</div>
                    </div>
                </div>
                <div class="flex flex-col justify-center pr-2">
                    <h4 class="font-black text-navy uppercase text-sm md:text-base leading-tight">IRONMAN 70.3<br>MIAMI, FL
                    </h4>
                    <p class="text-xs font-semibold text-navy/70 mt-1.5 mb-3 leading-snug">Fase pendaftaran ditutup untuk
                        atlet kategori kelompok umur master.</p>
                    <div>
                        <button
                            class="bg-yellow text-navy px-4 py-1.5 text-xs font-black uppercase rounded hover:bg-navy hover:text-yellow transition">REGISTER</button>
                    </div>
                </div>
            </div>

            <div class="event-card border border-gray-300 rounded-2xl p-4 flex gap-5 bg-transparent items-center">
                <div class="relative flex-shrink-0">
                    <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=200&h=140&fit=crop"
                        alt="Bike" class="w-40 h-28 object-cover rounded-xl shadow-sm">
                    <div
                        class="absolute top-1 left-1 flex flex-col w-10 shadow-md overflow-hidden rounded-md border border-navy/10">
                        <div class="bg-navy text-white text-[10px] font-bold text-center py-0.5 uppercase">JUN</div>
                        <div class="bg-white text-navy text-base font-black text-center py-0.5">27</div>
                    </div>
                </div>
                <div class="flex flex-col justify-center pr-2">
                    <h4 class="font-black text-navy uppercase text-sm md:text-base leading-tight">IRONMAN 70.3<br>MIAMI, FL
                    </h4>
                    <p class="text-xs font-semibold text-navy/70 mt-1.5 mb-3 leading-snug">Balap sepeda kecepatan tinggi
                        melintasi elevasi perbukitan terjal.</p>
                    <div>
                        <button
                            class="bg-yellow text-navy px-4 py-1.5 text-xs font-black uppercase rounded hover:bg-navy hover:text-yellow transition">REGISTER</button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-navy py-20 px-8 md:px-16" data-aos="fade-up">
        <h2 class="text-center font-oswald font-bold text-4xl text-white uppercase mb-12 tracking-wide">TOP-RANKED ATHLETES
        </h2>
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="athlete-card bg-cream rounded-2xl p-5 flex items-center relative shadow-lg">
                <img src="https://i.pravatar.cc/150?img=11" alt="Athlete"
                    class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-transparent">
                <div class="flex-1">
                    <h4 class="font-black text-navy text-sm uppercase leading-tight mb-1">NAME<br>GONDERSON</h4>
                    <p class="text-xs text-navy/70 font-bold mb-0.5">Current ranking 1</p>
                    <p class="text-xs text-navy font-bold">200 points</p>
                </div>
                <div class="absolute top-4 right-4 w-6 h-6 bg-yellow rounded flex items-center justify-center shadow-sm">
                    <svg class="w-3.5 h-3.5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="athlete-card bg-cream rounded-2xl p-5 flex items-center relative shadow-lg">
                <img src="https://i.pravatar.cc/150?img=5" alt="Athlete"
                    class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-transparent">
                <div class="flex-1">
                    <h4 class="font-black text-navy text-sm uppercase leading-tight mb-1">NOUK<br>KOOKER</h4>
                    <p class="text-xs text-navy/70 font-bold mb-0.5">Current ranking 2</p>
                    <p class="text-xs text-navy font-bold">175 points</p>
                </div>
                <div class="absolute top-4 right-4 w-6 h-6 bg-yellow rounded flex items-center justify-center shadow-sm">
                    <svg class="w-3.5 h-3.5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="athlete-card bg-cream rounded-2xl p-5 flex items-center relative shadow-lg">
                <img src="https://i.pravatar.cc/150?img=12" alt="Athlete"
                    class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-transparent">
                <div class="flex-1">
                    <h4 class="font-black text-navy text-sm uppercase leading-tight mb-1">JANNE<br>DALENSON</h4>
                    <p class="text-xs text-navy/70 font-bold mb-0.5">Current ranking 3</p>
                    <p class="text-xs text-navy font-bold">160 points</p>
                </div>
                <div class="absolute top-4 right-4 w-6 h-6 bg-yellow rounded flex items-center justify-center shadow-sm">
                    <svg class="w-3.5 h-3.5 text-navy" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                </div>
            </div>

        </div>
    </section>
@endsection
