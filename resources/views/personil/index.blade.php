@extends('layouts.main')

@section('title', 'Data Atlet, Pelatih, & Wasit FTI Lampung')

@section('content')
    <!-- HERO HEADER ELEGAN -->
    <section class="bg-navy pt-24 pb-16 px-8 md:px-16 text-center relative overflow-hidden">
        <!-- Efek Latar Belakang Halus -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-10 pointer-events-none">
            <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-white rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center">
            <!-- Logo FTI -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo FTI Lampung" class="h-40 md:h-56 lg:h-64 mb-8 object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-300">

            <span class="text-yellow text-xs font-black tracking-[0.3em] uppercase mb-3 block">FTI Provinsi Lampung</span>
            <h1 class="font-oswald text-white text-4xl md:text-6xl font-bold uppercase tracking-wide mb-6">DATABASE SDM OLAHRAGA</h1>
            <div class="w-16 h-1.5 bg-yellow mx-auto rounded-full"></div>
        </div>
    </section>

    <!-- BARIS ADMINISTRATOR -->
    @auth
        @if(auth()->user()->email == 'admin@triatlon.test')
            <div class="bg-white border-b border-gray-200 py-4 px-4 md:px-16 z-20 relative shadow-sm">
                <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-navy font-black text-xs uppercase tracking-widest flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2.5"></span> Mode Administrator Aktif
                    </p>
                    <div class="flex gap-2 w-full md:w-auto">
                        <a href="{{ url('/personil/create') }}" class="flex-1 md:flex-none text-center bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-yellow/80 transition-colors shadow-sm">
                            + TAMBAH DATA SDM
                        </a>
                        <a href="{{ url('/personil/kelola') }}" class="flex-1 md:flex-none text-center bg-navy text-white px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-navy/90 transition-colors shadow-sm">
                            KELOLA SDM
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 relative min-h-screen">
        <div class="max-w-6xl mx-auto">

            <!-- PANEL KONTROL: TAB & FILTER -->
            <div class="bg-white p-3 rounded-2xl shadow-md border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-12">

                <!-- Pill Tabs -->
                <div class="flex gap-1 w-full md:w-auto bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                    <button onclick="switchTab('atlet')" id="tab-btn-atlet" class="flex-1 md:w-32 py-2 text-center rounded-lg font-black text-[11px] uppercase tracking-wider transition-all duration-300 bg-navy text-yellow shadow-sm">
                        Atlet
                    </button>
                    <button onclick="switchTab('pelatih')" id="tab-btn-pelatih" class="flex-1 md:w-32 py-2 text-center rounded-lg font-black text-[11px] uppercase tracking-wider transition-all duration-300 bg-transparent text-navy/50 hover:text-navy">
                        Pelatih
                    </button>
                    <button onclick="switchTab('wasit')" id="tab-btn-wasit" class="flex-1 md:w-32 py-2 text-center rounded-lg font-black text-[11px] uppercase tracking-wider transition-all duration-300 bg-transparent text-navy/50 hover:text-navy">
                        Wasit
                    </button>
                </div>

                <!-- Dropdown Filter Daerah -->
                <div class="w-full md:w-72 flex items-center bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                    <svg class="w-4 h-4 text-navy/40 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <select id="filter-daerah" onchange="filterData()" class="w-full bg-transparent text-xs font-bold text-navy focus:outline-none cursor-pointer appearance-none">
                        <option value="Semua">Semua Wilayah Daerah</option>
                        @php
                            $wilayahLampung = ['Bandar Lampung', 'Metro', 'Pesawaran', 'Pringsewu', 'Tanggamus', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Utara', 'Lampung Barat', 'Lampung Timur', 'Way Kanan', 'Tulang Bawang', 'Tulang Bawang Barat', 'Mesuji', 'Pesisir Barat'];
                        @endphp
                        @foreach($wilayahLampung as $w)
                            <option value="{{ $w }}">{{ $w }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- TAB CONTAINER: ATLET                       -->
            <!-- ========================================== -->
            <div id="section-atlet" class="tab-content block space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="container-atlet">
                    @forelse($atlets as $atlet)
                        <div class="sdm-card bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col sm:flex-row gap-6 items-center" data-daerah="{{ $atlet->asal_daerah }}">
                            <div class="w-32 h-40 bg-gray-50 border border-gray-200 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-gray-300">
                                @if($atlet->foto)
                                    <img src="{{ asset($atlet->foto) }}" alt="{{ $atlet->nama }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @endif
                            </div>

                            <div class="flex-1 w-full flex flex-col justify-between h-40">
                                <div>
                                    <div class="flex justify-between items-start gap-2 mb-2">
                                        <h3 class="font-black text-navy text-base uppercase leading-tight">{{ $atlet->nama }}</h3>
                                        <span class="bg-gray-100 text-navy text-[9px] font-black uppercase px-2 py-1 rounded border border-gray-200 shrink-0">{{ $atlet->asal_daerah }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 mt-4 text-[11px] font-semibold text-navy/70">
                                        <div>
                                            <p class="text-[9px] font-bold text-navy/40 uppercase tracking-wider">Lahir</p>
                                            <p class="uppercase mt-0.5">{{ $atlet->ttl ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-bold text-navy/40 uppercase tracking-wider">Usia</p>
                                            <p class="mt-0.5">{{ $atlet->umur ? $atlet->umur . ' Tahun' : '-' }}</p>
                                        </div>
                                        <div class="col-span-2">
                                            <p class="text-[9px] font-bold text-navy/40 uppercase tracking-wider">Identitas ({{ $atlet->jenis_identitas ?? 'ID' }})</p>
                                            <p class="mt-0.5 font-mono font-bold text-navy">{{ $atlet->nomor_identitas ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->user()->email == 'admin@triatlon.test' && $atlet->kontak)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $atlet->kontak) }}" target="_blank" class="w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold text-[10px] uppercase py-2 rounded-lg tracking-wider transition-colors block mt-2">
                                            Hubungi via WhatsApp
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center w-full py-12 col-span-full">Belum ada data atlet terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB CONTAINER: PELATIH                     -->
            <!-- ========================================== -->
            <div id="section-pelatih" class="tab-content hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="container-pelatih">
                    @forelse($pelatihs as $pelatih)
                        <div class="sdm-card bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-gray-200 text-center flex flex-col justify-between min-h-[280px]" data-daerah="{{ $pelatih->asal_daerah }}">
                            <div>
                                <div class="w-20 h-20 mx-auto bg-gray-50 border border-gray-200 rounded-full mb-4 overflow-hidden flex items-center justify-center text-gray-300">
                                    @if($pelatih->foto)
                                        <img src="{{ asset($pelatih->foto) }}" alt="{{ $pelatih->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @endif
                                </div>
                                <h3 class="font-black text-navy text-sm uppercase mb-2 leading-tight min-h-[36px] flex items-center justify-center">{{ $pelatih->nama }}</h3>
                                <p class="text-[9px] font-black text-yellow bg-navy px-2.5 py-1 rounded uppercase tracking-wider mb-3 inline-block">{{ $pelatih->tingkat_lisensi ?? 'Belum Ada Lisensi' }}</p>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest border-t border-gray-100 pt-2 mt-1">Daerah: {{ $pelatih->asal_daerah }}</p>
                            </div>

                            <div class="space-y-2 mt-4">
                                @if($pelatih->sertifikat_lisensi)
                                    <button onclick="openLicenseModal('{{ asset($pelatih->sertifikat_lisensi) }}')" class="w-full bg-gray-50 border border-gray-200 text-navy hover:bg-navy hover:text-white transition-colors py-2 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                        Sertifikat Lisensi
                                    </button>
                                @endif

                                @auth
                                    @if(auth()->user()->email == 'admin@triatlon.test' && $pelatih->kontak)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pelatih->kontak) }}" target="_blank" class="w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold text-[10px] uppercase py-2 rounded-lg tracking-wider transition-colors block">
                                            WhatsApp
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center w-full py-12 col-span-full">Belum ada data pelatih terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB CONTAINER: WASIT                       -->
            <!-- ========================================== -->
            <div id="section-wasit" class="tab-content hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="container-wasit">
                    @forelse($wasits as $wasit)
                        <div class="sdm-card bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-gray-200 text-center flex flex-col justify-between min-h-[280px]" data-daerah="{{ $wasit->asal_daerah }}">
                            <div>
                                <div class="w-20 h-20 mx-auto bg-gray-50 border border-gray-200 rounded-full mb-4 overflow-hidden flex items-center justify-center text-gray-300">
                                    @if($wasit->foto)
                                        <img src="{{ asset($wasit->foto) }}" alt="{{ $wasit->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @endif
                                </div>
                                <h3 class="font-black text-navy text-sm uppercase mb-2 leading-tight min-h-[36px] flex items-center justify-center">{{ $wasit->nama }}</h3>
                                <p class="text-[9px] font-black text-navy bg-yellow px-2.5 py-1 rounded uppercase tracking-wider mb-3 inline-block">{{ $wasit->tingkat_lisensi ?? 'Wasit Daerah' }}</p>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest border-t border-gray-100 pt-2 mt-1">Tugas: {{ $wasit->asal_daerah }}</p>
                            </div>

                            <div class="space-y-2 mt-4">
                                @if($wasit->sertifikat_lisensi)
                                    <button onclick="openLicenseModal('{{ asset($wasit->sertifikat_lisensi) }}')" class="w-full bg-gray-50 border border-gray-200 text-navy hover:bg-navy hover:text-white transition-colors py-2 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                        Sertifikat Lisensi
                                    </button>
                                @endif

                                @auth
                                    @if(auth()->user()->email == 'admin@triatlon.test' && $wasit->kontak)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wasit->kontak) }}" target="_blank" class="w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold text-[10px] uppercase py-2 rounded-lg tracking-wider transition-colors block">
                                            WhatsApp
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center w-full py-12 col-span-full">Belum ada data wasit terdaftar.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <!-- MODAL POPUP PDF -->
    <div id="licenseModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-10">
        <div class="absolute inset-0 bg-navy/90 backdrop-blur-sm cursor-pointer" onclick="closeLicenseModal()"></div>
        <div class="bg-white w-full max-w-4xl h-[85vh] rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden">
            <div class="flex justify-between items-center p-5 border-b border-gray-200 bg-gray-50">
                <h3 class="font-black text-navy uppercase text-sm">Dokumen Lisensi SDM FTI</h3>
                <button onclick="closeLicenseModal()" class="text-red-500 font-black text-xs uppercase hover:underline">Tutup</button>
            </div>
            <div class="flex-1 bg-gray-200">
                <iframe id="licenseIframe" src="" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGIKA -->
    <script>
        // Logika Perpindahan Tab
        function switchTab(type) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.replace('block', 'hidden'));
            document.getElementById(`section-${type}`).classList.replace('hidden', 'block');

            const btns = [document.getElementById('tab-btn-atlet'), document.getElementById('tab-btn-pelatih'), document.getElementById('tab-btn-wasit')];
            btns.forEach(b => {
                b.classList.remove('bg-navy', 'text-yellow', 'shadow-sm');
                b.classList.add('bg-transparent', 'text-navy/50');
            });

            const activeBtn = document.getElementById(`tab-btn-${type}`);
            activeBtn.classList.add('bg-navy', 'text-yellow', 'shadow-sm');
            activeBtn.classList.remove('bg-transparent', 'text-navy/50');

            // Reset filter ke Semua saat ganti tab agar tidak bingung
            document.getElementById('filter-daerah').value = 'Semua';
            filterData();
        }

        // Logika Live Filter Daerah Instan
        function filterData() {
            const filterValue = document.getElementById('filter-daerah').value;
            const cards = document.querySelectorAll('.sdm-card');

            cards.forEach(card => {
                if (filterValue === 'Semua' || card.dataset.daerah === filterValue) {
                    card.style.display = ''; // Mengembalikan ke pengaturan display bawaan class Tailwind
                } else {
                    card.style.display = 'none'; // Menyembunyikan card
                }
            });
        }

        // Logika Popup PDF
        function openLicenseModal(url) {
            document.getElementById('licenseIframe').src = url;
            document.getElementById('licenseModal').classList.replace('hidden', 'flex');
        }

        function closeLicenseModal() {
            document.getElementById('licenseModal').classList.replace('flex', 'hidden');
            document.getElementById('licenseIframe').src = '';
        }
    </script>
@endsection
