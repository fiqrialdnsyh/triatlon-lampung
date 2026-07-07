@extends('layouts.main')

@section('title', 'Pengurus FTI Lampung')

@section('content')
    <!-- Hero Header Section -->
    <section class="bg-navy py-20 px-8 md:px-16 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20 pointer-events-none">
            <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-yellow rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10">
            <span class="text-yellow text-sm font-black tracking-widest uppercase mb-4 block">Federasi Triathlon Indonesia</span>
            <h1 class="font-oswald text-white text-4xl md:text-6xl font-bold uppercase tracking-wide mb-6">STRUKTUR ORGANISASI<br>FTI PROVINSI LAMPUNG</h1>
            <p class="text-white/70 font-semibold max-w-2xl mx-auto text-sm md:text-base">Mengenal lebih dekat jajaran kepengurusan tingkat provinsi (Pusat) dan pengurus cabang (Pengcab) tingkat kabupaten/kota di wilayah Lampung.</p>
        </div>
    </section>

    <!-- TOMBOL KONTROL ADMINISTRATOR -->
    @auth
        @if(auth()->user()->email == 'admin@triatlon.test')
            <div class="bg-navy border-t border-white/10 py-6 px-8 md:px-16 z-20 relative">
                <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-yellow font-bold text-xs uppercase tracking-widest flex items-center">
                        Mode Administrator Aktif
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ url('/pengurus/create') }}" class="bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-white transition-colors shadow-lg">
                            + INPUT DATA PENGURUS
                        </a>
                        <a href="{{ route('pengurus.kelola') }}" class="bg-white/10 text-white border border-white/20 px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-white hover:text-navy transition-colors shadow-lg">
                            KELOLA PENGURUS
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <!-- SECTION UTAMA KEPENGURUSAN PROVINSI -->
    <section class="bg-cream py-24 px-8 md:px-16 relative">
        <div class="max-w-6xl mx-auto">

            @php
                // Mencari satu file SK Provinsi dari data yang ada
                $skProvinsiObj = $inti->whereNotNull('file_sk')->first() ?? $dewan->whereNotNull('file_sk')->first() ?? $bidang->whereNotNull('file_sk')->first();
                $skProvinsi = $skProvinsiObj ? $skProvinsiObj->file_sk : null;
            @endphp

            <!-- 1. BAGIAN DEWAN (PEMBINA / PENASIHAT) -->
            <div class="text-center mb-16">
                <span class="text-navy/50 text-xs font-black tracking-widest uppercase mb-2 block">Penasihat Organisasi</span>
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy">Dewan Pembina & Penasihat</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-6 mb-24">
                @forelse($dewan as $d)
                    @php
                        $namaDewan = $d->nama !== '-' && !empty($d->nama) ? $d->nama : '';
                        $jabatanDewan = $d->jabatan !== '-' && !empty($d->jabatan) ? $d->jabatan : '';
                        $textUtama = $namaDewan ?: $jabatanDewan;
                        $textKedua = $namaDewan && $jabatanDewan ? $jabatanDewan : '';
                    @endphp

                    <div class="bg-white p-6 rounded-[1.5rem] shadow-xl border border-gray-100 text-center w-full max-w-[240px] flex flex-col justify-center transform hover:-translate-y-1 transition-transform duration-300 min-h-[140px]">
                        <h3 class="font-black text-navy text-base uppercase mb-2 leading-tight">{{ $textUtama }}</h3>
                        @if($textKedua)
                            <div>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest bg-gray-50 inline-block px-3 py-1 rounded">{{ $textKedua }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-xs font-bold text-navy/40 uppercase tracking-widest text-center w-full">Struktur Dewan belum dimasukkan.</p>
                @endforelse
            </div>

            <!-- 2. BAGIAN PENGURUS INTI (HIERARKIS KETUA DI ATAS) -->
            <div class="text-center mb-16">
                <span class="text-yellow text-xs font-black tracking-widest uppercase mb-2 block">Pusat Komando</span>
                <h2 class="font-oswald text-4xl font-bold uppercase text-navy">PENGURUS HARIAN (INTI)</h2>
            </div>

            @php
                $ketuaUmum = $inti->filter(fn($item) => Str::contains(strtolower($item->jabatan), 'ketua umum'))->first();
                $intiLainnya = $inti->filter(fn($item) => !Str::contains(strtolower($item->jabatan), 'ketua umum'));
            @endphp

            <!-- Baris Ketua Umum -->
            @if($ketuaUmum)
                <div class="flex justify-center mb-12">
                    <div class="bg-navy p-8 rounded-[2rem] text-center w-full max-w-[340px] shadow-2xl border-4 border-yellow transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-20 h-20 mx-auto bg-white/10 text-yellow rounded-full mb-4 flex items-center justify-center font-black text-2xl uppercase">
                            {{ substr($ketuaUmum->nama, 0, 1) }}
                        </div>
                        <h3 class="font-black text-white text-lg uppercase mb-1 leading-tight">{{ $ketuaUmum->nama }}</h3>
                        <p class="text-xs font-bold uppercase tracking-widest text-yellow bg-white/10 px-3 py-1 rounded inline-block mt-2">
                            {{ $ketuaUmum->jabatan }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Baris Pengurus Harian Lainnya -->
            <div class="flex flex-wrap justify-center gap-6 mb-24">
                @forelse($intiLainnya as $i)
                    <div class="bg-white p-6 rounded-[2rem] shadow-xl border border-gray-100 text-center w-full max-w-[260px] flex flex-col justify-center transform hover:-translate-y-1 transition-transform duration-300 min-h-[160px]">
                        <div class="w-16 h-16 mx-auto bg-gray-100 text-navy rounded-full mb-4 flex items-center justify-center font-black text-xl uppercase">
                            {{ substr($i->nama, 0, 1) }}
                        </div>
                        <h3 class="font-black text-navy text-sm uppercase mb-1 leading-tight">{{ $i->nama }}</h3>
                        <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">{{ $i->jabatan }}</p>
                    </div>
                @empty
                    @if(!$ketuaUmum)
                        <p class="text-xs font-bold text-navy/40 uppercase tracking-widest text-center w-full">Struktur Pengurus Inti belum dimasukkan.</p>
                    @endif
                @endforelse
            </div>

            <!-- 3. BAGIAN BIDANG DAN ANGGOTA -->
            @php
                $bidangGrouped = $bidang->groupBy(function($item) {
                    return !empty($item->keterangan) ? $item->keterangan : 'Bidang / Komisi';
                });
            @endphp

            <div class="text-center mb-16">
                <span class="text-navy/50 text-xs font-black tracking-widest uppercase mb-2 block">Divisi Pelaksana</span>
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy">Komisi & Bidang-Bidang</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($bidangGrouped as $namaBidang => $kumpulanAnggota)
                    @php
                        $ketuaBidang = $kumpulanAnggota->filter(fn($x) => Str::contains(strtolower($x->jabatan), 'ketua'))->first();
                        $anggotaSaja = $kumpulanAnggota->filter(fn($x) => !Str::contains(strtolower($x->jabatan), 'ketua'));
                    @endphp

                    <div class="bg-white p-8 rounded-[2rem] border border-gray-200 shadow-xl relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <div class="border-b border-navy/10 pb-4 mb-4">
                                <h3 class="font-black text-navy uppercase text-base leading-tight">{{ $namaBidang }}</h3>
                            </div>

                            <ul class="space-y-3">
                                @if($ketuaBidang)
                                    <li class="bg-yellow/10 p-3 rounded-xl border border-yellow/30">
                                        <p class="text-[9px] font-black text-yellow-600 uppercase tracking-widest">{{ $ketuaBidang->jabatan }}</p>
                                        <p class="text-sm font-black text-navy uppercase">{{ $ketuaBidang->nama }}</p>
                                    </li>
                                @endif

                                @foreach($anggotaSaja as $a)
                                    <li class="px-3 py-1 flex items-start gap-2">
                                        <div class="w-1.5 h-1.5 bg-navy/30 rounded-full mt-2 shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-bold text-navy/80 uppercase leading-tight">{{ $a->nama }}</p>
                                            @if($a->jabatan && !Str::contains(strtolower($a->jabatan), 'anggota'))
                                                <p class="text-[9px] font-bold text-navy/40 uppercase">{{ $a->jabatan }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center">
                        <p class="text-xs font-bold text-navy/40 uppercase tracking-widest w-full">Belum ada data Komisi & Bidang.</p>
                    </div>
                @endforelse
            </div>

            <!-- TOMBOL GLOBAL SK PROVINSI DI BAWAH BIDANG -->
            @if($skProvinsi)
                <div class="mt-20 text-center">
                    <button onclick="openSkModal('{{ asset($skProvinsi) }}')" class="bg-navy text-yellow px-10 py-5 rounded-[1.5rem] font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-xl inline-flex items-center group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        LIHAT SK KEPENGURUSAN PROVINSI
                    </button>
                </div>
            @endif

        </div>
    </section>

    <!-- SECTION KEPENGURUSAN DAERAH (KABUPATEN / KOTA) -->
    <section class="bg-navy py-24 px-8 md:px-16 border-t border-white/10 relative overflow-hidden">
        <div class="max-w-6xl mx-auto relative z-10">

            @php
                $cabangGrouped = $cabang->groupBy('nama_daerah');
            @endphp

            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
                    <span class="text-yellow text-sm font-black tracking-widest uppercase mb-2 block">Kepengurusan Daerah</span>
                    <h2 class="font-oswald text-4xl font-bold uppercase text-white">PENGURUS CABANG KAB/KOTA</h2>
                </div>
                <p class="text-white/50 text-xs font-bold uppercase mt-4 md:mt-0 tracking-wider">Total Wilayah: {{ $cabangGrouped->count() }} Pengcab</p>
            </div>

            <!-- List Card Ringkas Kabupaten/Kota -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($cabangGrouped as $daerah => $pengurusCabang)
                    @php
                        $ketuaCabang = $pengurusCabang->filter(fn($p) => Str::contains(strtolower($p->jabatan), 'ketua'))->first();
                        $infoDaerah = $pengurusCabang->first();
                        $namaKetua = $ketuaCabang ? $ketuaCabang->nama : 'Belum Ditentukan';
                    @endphp

                    <div class="bg-white/5 border border-white/10 p-6 rounded-2xl hover:bg-white/10 hover:border-yellow/50 transition-all duration-300 flex flex-col justify-between shadow-lg">
                        <a href="{{ url('/pengurus/cabang/' . Str::slug($daerah)) }}" class="block group cursor-pointer">
                            <div class="flex justify-between items-start gap-4 mb-4">
                                <h3 class="font-black text-white group-hover:text-yellow text-lg uppercase tracking-wide transition-colors leading-tight">{{ $daerah }}</h3>
                                @if($infoDaerah && strtolower($infoDaerah->status_cabang) == 'aktif')
                                    <span class="bg-green-500/20 text-green-400 text-[8px] font-black uppercase px-2 py-0.5 rounded tracking-wider">AKTIF</span>
                                @else
                                    <span class="bg-yellow-500/20 text-yellow text-[8px] font-black uppercase px-2 py-0.5 rounded tracking-wider">VAKUM</span>
                                @endif
                            </div>

                            <div class="mt-4">
                                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest">Ketua Pengcab</p>
                                <p class="text-sm font-bold text-white uppercase mt-0.5 group-hover:translate-x-1 transition-transform inline-block">{{ $namaKetua }}</p>
                            </div>

                            <div class="mt-6 pt-3 border-t border-white/5 flex justify-between items-center">
                                <span class="text-[9px] font-black text-yellow uppercase tracking-widest opacity-80 group-hover:opacity-100 transition-opacity">
                                    Lihat Struktur Lengkap →
                                </span>
                            </div>
                        </a>

                        <!-- Tombol Buka SK Modal Khusus Cabang (Jika ada) -->
                        @if($infoDaerah && $infoDaerah->file_sk)
                            <button onclick="openSkModal('{{ asset($infoDaerah->file_sk) }}')" class="mt-4 w-full flex items-center justify-center text-center bg-white/10 hover:bg-yellow hover:text-navy text-white text-[10px] font-black uppercase px-4 py-3 rounded-xl transition-colors">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                BUKA SK DAERAH
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full border-2 border-dashed border-white/20 p-12 rounded-[2rem] flex flex-col items-center justify-center text-center">
                        <p class="font-black text-white/50 uppercase text-sm tracking-wider">Belum ada data Pengurus Cabang yang dimasukkan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- MODAL POPUP UNTUK DOKUMEN SK -->
    <div id="skModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-10">
        <!-- Latar Belakang Gelap (Klik untuk menutup) -->
        <div class="absolute inset-0 bg-navy/90 backdrop-blur-sm cursor-pointer transition-opacity" onclick="closeSkModal()"></div>

        <!-- Konten Modal -->
        <div class="bg-cream w-full max-w-5xl h-[85vh] md:h-[90vh] rounded-[2rem] shadow-2xl relative z-10 flex flex-col overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="skModalContent">

            <!-- Header Modal -->
            <div class="flex justify-between items-center p-5 md:p-6 border-b border-navy/10 bg-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-navy/5 rounded-xl flex items-center justify-center mr-4 text-navy">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-navy uppercase text-sm md:text-base leading-tight">Dokumen Surat Keputusan</h3>
                        <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Federasi Triathlon Indonesia</p>
                    </div>
                </div>

                <button onclick="closeSkModal()" class="text-red-500 font-black hover:text-white hover:bg-red-500 bg-red-50 px-4 py-2 rounded-lg text-xs tracking-wider transition-colors flex items-center">
                    TUTUP <span class="hidden md:inline ml-1">DOKUMEN</span>
                </button>
            </div>

            <!-- Iframe Pembaca PDF -->
            <div class="flex-1 w-full bg-gray-200 relative">
                <!-- Indikator Loading -->
                <div id="skLoading" class="absolute inset-0 flex flex-col items-center justify-center text-navy/40">
                    <svg class="w-10 h-10 animate-spin mb-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-xs font-bold uppercase tracking-widest">Memuat Dokumen PDF...</p>
                </div>

                <iframe id="skIframe" src="" class="w-full h-full border-0 relative z-10" onload="document.getElementById('skLoading').style.display='none'"></iframe>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA MODAL -->
    <script>
        function openSkModal(skUrl) {
            const modal = document.getElementById('skModal');
            const modalContent = document.getElementById('skModalContent');
            const iframe = document.getElementById('skIframe');
            const loading = document.getElementById('skLoading');

            // Tampilkan loading kembali setiap kali dibuka
            loading.style.display = 'flex';

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animasi masuk
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Masukkan URL ke iframe
            iframe.src = skUrl;

            // Kunci scroll halaman belakang
            document.body.style.overflow = 'hidden';
        }

        function closeSkModal() {
            const modal = document.getElementById('skModal');
            const modalContent = document.getElementById('skModalContent');
            const iframe = document.getElementById('skIframe');

            // Animasi keluar
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                iframe.src = ''; // Hapus source agar tidak terus memuat di latar belakang
                document.body.style.overflow = 'auto'; // Kembalikan scroll
            }, 300); // Waktu yang sama dengan durasi animasi
        }
    </script>
@endsection
