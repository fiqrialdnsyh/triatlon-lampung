@extends('layouts.main')
@section('title', $mainEvent->judul . ' - FTI LAMPUNG')

@section('content')
    <section class="bg-[#F4F6F9] py-12 px-6 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

            <!-- KOLOM KIRI: Detail Event -->
            <div class="lg:w-5/12 space-y-6">
                <a href="{{ route('main_event.index') }}"
                    class="inline-flex items-center text-navy/50 hover:text-navy text-xs font-black uppercase tracking-wider transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Kalender
                </a>

                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                    @if ($mainEvent->poster)
                        <img src="{{ asset($mainEvent->poster) }}" class="w-full object-cover border-b border-gray-100">
                    @endif
                    <div class="p-8">
                        <h1 class="font-oswald text-3xl font-bold uppercase text-navy leading-tight mb-4">
                            {{ $mainEvent->judul }}</h1>

                        <div class="space-y-4 mb-6">
                            <div class="flex items-start gap-3">
                                <div class="bg-navy/5 p-2 rounded shrink-0"><svg class="w-5 h-5 text-navy" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg></div>
                                <div>
                                    <p class="text-[10px] font-black text-navy/40 uppercase tracking-widest">Pelaksanaan</p>
                                    <p class="text-sm font-bold text-navy">
                                        {{ \Carbon\Carbon::parse($mainEvent->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="bg-yellow/10 p-2 rounded shrink-0"><svg class="w-5 h-5 text-yellow-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                    </svg></div>
                                <div>
                                    <p class="text-[10px] font-black text-navy/40 uppercase tracking-widest">Lokasi Venue
                                    </p>
                                    <p class="text-sm font-bold text-navy">{{ $mainEvent->lokasi }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <h4 class="font-black text-navy uppercase text-sm mb-2">Informasi Acara</h4>
                            <p class="text-sm font-medium text-navy/70 leading-relaxed text-justify whitespace-pre-line">
                                {{ $mainEvent->deskripsi }}</p>
                        </div>

                        @php
                            // Asumsi THB menempel pada sub-event (diambil dari eventOpen jika ada, atau eventKejurnas)
                            $thbFile = $eventOpen
                                ? $eventOpen->thb_file
                                : ($eventKejurnas
                                    ? $eventKejurnas->thb_file
                                    : null);
                        @endphp
                        @if ($thbFile)
                            <div class="mt-6 border-t border-gray-100 pt-6">
                                <a href="{{ asset($thbFile) }}" target="_blank"
                                    class="flex items-center justify-center gap-2 w-full bg-navy text-white hover:text-yellow py-3.5 rounded-xl font-black text-xs uppercase tracking-widest transition-colors shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Unduh Buku Panduan (THB)
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Formulir Pendaftaran dengan Tab Toggle -->
            <div class="lg:w-7/12">
                <div class="bg-white rounded-3xl border border-gray-200 shadow-xl overflow-hidden">

                    @if (session('success'))
                        <div class="m-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl font-black text-sm uppercase tracking-wide text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="m-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-xs font-bold text-red-700">
                            Pendaftaran gagal. Periksa kembali kelengkapan form dan aturan file (Max 3MB).
                        </div>
                    @endif

                    <!-- NAVIGASI TAB -->
                    <div class="flex bg-navy p-1.5 gap-1.5">
                        @if ($mainEvent->is_open_active)
                            <button id="btn-open" onclick="switchTab('open')"
                                class="flex-1 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $mainEvent->is_open_active ? 'bg-yellow text-navy shadow-md' : 'text-white/50 hover:text-white' }}">
                                Jalur Open
                            </button>
                        @endif

                        @if ($mainEvent->is_kejurnas_active)
                            <button id="btn-kejurnas" onclick="switchTab('kejurnas')"
                                class="flex-1 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ !$mainEvent->is_open_active && $mainEvent->is_kejurnas_active ? 'bg-yellow text-navy shadow-md' : 'text-white/50 hover:text-white' }}">
                                Jalur Kejurnas
                            </button>
                        @endif
                    </div>

                    <div class="p-8">
                        <!-- KONTEN TAB OPEN -->
                        @if ($mainEvent->is_open_active)
                            <div id="tab-open" class="{{ $mainEvent->is_open_active ? 'block' : 'hidden' }} animate-fadeIn">
                                @if ($sudahDaftarOpen)
                                    <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center">
                                        <div class="w-14 h-14 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <h4 class="font-black text-green-700 uppercase mb-2">Anda Sudah Terdaftar</h4>
                                        <p class="text-xs font-bold text-green-600 mb-6">Pendaftaran Anda untuk Jalur Open sedang diproses atau sudah tervalidasi.</p>
                                        <a href="{{ route('event.open.history') }}" class="inline-block bg-navy text-yellow px-6 py-3 rounded-xl font-black text-xs uppercase hover:bg-yellow hover:text-navy transition-colors shadow-md">Cek Status & Tiket</a>
                                    </div>
                                @elseif($eventOpen->status !== 'Buka')
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center">
                                        <svg class="w-10 h-10 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        <p class="text-red-700 font-black uppercase text-sm">Pendaftaran Jalur Open Telah Ditutup</p>
                                    </div>
                                @else
                                    <div class="bg-navy/5 text-navy p-4 rounded-xl mb-6 text-xs font-bold border border-navy/10 flex items-start gap-3">
                                        <svg class="w-5 h-5 shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p>Jalur ini terbuka untuk pendaftar individu umum. Siapkan berkas identitas dan bukti transfer pendaftaran.</p>
                                    </div>

                                    <form action="{{ route('event.open.register', $eventOpen->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                        @csrf

                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Biodata Pribadi</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                <div class="md:col-span-2">
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                                                    <input type="text" name="nama_lengkap" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></label>
                                                    <input type="number" name="nomor_ktp" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nama Bib (Maks 15 Huruf) <span class="text-red-500">*</span></label>
                                                    <input type="text" name="bib_name" maxlength="15" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy uppercase focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                                                    <input type="text" name="tempat_lahir" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                                                    <input type="date" name="tanggal_lahir" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                                    <select name="jenis_kelamin" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                        <option value="Putra">Putra</option>
                                                        <option value="Putri">Putri</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Golongan Darah <span class="text-red-500">*</span></label>
                                                    <select name="golongan_darah" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                        <option value="A+">A+</option><option value="A-">A-</option>
                                                        <option value="B+">B+</option><option value="B-">B-</option>
                                                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                                        <option value="O+">O+</option><option value="O-">O-</option>
                                                        <option value="Tidak Tahu">Tidak Tahu</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Alamat Domisili <span class="text-red-500">*</span></label>
                                                    <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Kabupaten / Kota Asal <span class="text-red-500">*</span></label>
                                                    <input type="text" name="asal_daerah" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Email Aktif <span class="text-red-500">*</span></label>
                                                    <input type="email" name="email" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                                                    <input type="number" name="nomor_telepon" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Pilih Nomor Perlombaan <span class="text-red-500">*</span></label>
                                                    <select name="kategori_lomba" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-black text-navy focus:outline-none focus:ring-2 focus:ring-yellow" required>
                                                        <option value="" disabled selected>-- Tentukan Nomor --</option>
                                                        @foreach (explode(', ', $eventOpen->kategori_lomba) as $kat)
                                                            @if (trim($kat) !== '')
                                                                <option value="{{ trim($kat) }}">{{ trim($kat) }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Pilih Golongan Biaya & Unggah Bukti Transfer</h4>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                                @php $skemaBiayaOpen = json_decode($eventOpen->skema_biaya, true); @endphp
                                                @if ($skemaBiayaOpen)
                                                    @foreach ($skemaBiayaOpen as $idx => $skema)
                                                        <label class="flex items-center space-x-3 bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-navy transition-colors">
                                                            <input type="radio" name="golongan_biaya" value="{{ $skema['nama'] }}" class="w-4 h-4 accent-navy" {{ $idx == 0 ? 'checked' : '' }} required>
                                                            <span class="text-xs font-bold text-navy">{{ $skema['nama'] }} (Rp {{ number_format($skema['biaya'], 0, ',', '.') }})</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <div class="bg-yellow/15 border border-yellow-300 rounded-xl p-4 mb-4">
                                                <p class="text-[10px] font-black text-yellow-800 uppercase tracking-widest mb-1">Transfer Pembayaran Ke:</p>
                                                <p class="text-sm font-black text-navy uppercase">{{ $eventOpen->nama_bank }} - {{ $eventOpen->nomor_rekening }}</p>
                                                <p class="text-xs font-bold text-navy/70 uppercase">A.n {{ $eventOpen->atas_nama }}</p>
                                            </div>

                                            <input type="file" name="bukti_transfer" accept="image/*" class="block w-full text-xs text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer bg-white border border-gray-200 rounded-lg p-1" required>
                                        </div>

                                        <button type="submit" class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                                            Kirim Pendaftaran Open
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif

                        <!-- KONTEN TAB KEJURNAS -->
                        @if ($mainEvent->is_kejurnas_active)
                            <div id="tab-kejurnas" class="{{ !$mainEvent->is_open_active && $mainEvent->is_kejurnas_active ? 'block' : 'hidden' }} animate-fadeIn">
                                @if ($eventKejurnas->status !== 'Buka')
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center">
                                        <svg class="w-10 h-10 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        <p class="text-red-700 font-black uppercase text-sm">Pendaftaran Kontingen Telah Ditutup</p>
                                    </div>
                                @elseif(auth()->check() && auth()->user()->role == 'kontingen')
                                    <div class="bg-navy/5 text-navy p-4 rounded-xl mb-6 text-xs font-bold border border-navy/10 flex items-start gap-3">
                                        <svg class="w-5 h-5 shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p>Sesi Kontingen Resmi. Anda login sebagai <strong>{{ auth()->user()->name }}</strong>. Silakan daftarkan data atlet delegasi Anda di bawah ini.</p>
                                    </div>

                                    <form action="{{ route('event.kejurnas.register', $eventKejurnas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                        @csrf
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Biodata Pribadi Atlet</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                <div class="md:col-span-2">
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nama Lengkap Atlet <span class="text-red-500">*</span></label>
                                                    <input type="text" name="nama_lengkap" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nomor KTP / KIA Atlet <span class="text-red-500">*</span></label>
                                                    <input type="number" name="nomor_ktp" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nama Bib (Maks 15 Huruf) <span class="text-red-500">*</span></label>
                                                    <input type="text" name="bib_name" maxlength="15" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy uppercase focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                                                    <input type="text" name="tempat_lahir" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                                                    <input type="date" name="tanggal_lahir" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                                    <select name="jenis_kelamin" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                        <option value="Putra">Putra</option>
                                                        <option value="Putri">Putri</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Golongan Darah <span class="text-red-500">*</span></label>
                                                    <select name="golongan_darah" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                        <option value="A+">A+</option><option value="A-">A-</option>
                                                        <option value="B+">B+</option><option value="B-">B-</option>
                                                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                                        <option value="O+">O+</option><option value="O-">O-</option>
                                                        <option value="Tidak Tahu">Tidak Tahu</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Alamat Atlet <span class="text-red-500">*</span></label>
                                                    <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Delegasi Daerah <span class="text-red-500">*</span></label>
                                                    <input type="text" name="asal_daerah" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Email Atlet <span class="text-red-500">*</span></label>
                                                    <input type="email" name="email" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                                    <input type="number" name="nomor_telepon" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy uppercase mb-1">Pilih Nomor Perlombaan Atlet <span class="text-red-500">*</span></label>
                                                    <select name="kategori_lomba" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-black text-navy focus:outline-none focus:ring-2 focus:ring-yellow" required>
                                                        <option value="" disabled selected>-- Tentukan Nomor --</option>
                                                        @foreach (explode(', ', $eventKejurnas->kategori_lomba) as $kat)
                                                            @if (trim($kat) !== '')
                                                                <option value="{{ trim($kat) }}">{{ trim($kat) }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Pilih Golongan Biaya Kontingen & Bukti Transfer</h4>

                                            <div class="grid grid-cols-1 gap-3 mb-4">
                                                @php $skemaBiayaKejurnas = json_decode($eventKejurnas->skema_biaya, true); @endphp
                                                @if ($skemaBiayaKejurnas)
                                                    @foreach ($skemaBiayaKejurnas as $idx => $skema)
                                                        <label class="flex items-center space-x-3 bg-white border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-navy transition-colors">
                                                            <input type="radio" name="golongan_biaya" value="{{ $skema['nama'] }}" class="w-4 h-4 accent-navy" {{ $idx == 0 ? 'checked' : '' }} required>
                                                            <span class="text-xs font-bold text-navy">{{ $skema['nama'] }} (Rp {{ number_format($skema['biaya'], 0, ',', '.') }})</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>

                                            <input type="file" name="bukti_transfer" accept="image/*" class="block w-full text-xs text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer bg-white border border-gray-200 rounded-lg p-1" required>
                                        </div>

                                        <button type="submit" class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                                            Daftarkan Atlet Kontingen
                                        </button>
                                    </form>

                                    <div class="mt-8 bg-gray-50 border border-gray-200 rounded-2xl p-6">
                                        <h4 class="font-black text-navy uppercase text-sm mb-4 pb-2 border-b border-gray-200 flex items-center justify-between">
                                            <span>Daftar Atlet Delegasi Anda</span>
                                            <span class="bg-navy text-yellow px-2.5 py-1 rounded-full text-[10px]">{{ count($myAtletRegistrations) }} Atlet</span>
                                        </h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-[11px] border-collapse">
                                                <thead>
                                                    <tr class="bg-navy text-white uppercase font-black tracking-wider">
                                                        <th class="p-3 w-10 text-center rounded-l-lg">No</th>
                                                        <th class="p-3">Nama & BIB</th>
                                                        <th class="p-3">Kategori Lomba</th>
                                                        <th class="p-3 text-center rounded-r-lg">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 font-semibold text-navy/80 bg-white">
                                                    @php $no_urut = 1; @endphp
                                                    @forelse($myAtletRegistrations as $atlet)
                                                        <tr>
                                                            <td class="p-3 text-center">{{ $no_urut++ }}</td>
                                                            <td class="p-3">
                                                                <p class="font-black uppercase text-navy">{{ $atlet->nama_lengkap }}</p>
                                                                <p class="text-[9px] text-gray-500 uppercase mt-0.5">BIB: {{ $atlet->bib_name }}</p>
                                                            </td>
                                                            <td class="p-3">{{ $atlet->kategori_lomba }}</td>
                                                            <td class="p-3 text-center">
                                                                <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-wider
                                                                    {{ $atlet->status_pembayaran == 'Valid' ? 'bg-green-100 text-green-700' : ($atlet->status_pembayaran == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow/20 text-yellow-700 border border-yellow/30') }}">
                                                                    {{ $atlet->status_pembayaran }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="p-4 text-center font-bold text-gray-400 uppercase tracking-widest text-[10px]">Anda belum mendaftarkan atlet satupun.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-red-50 border border-red-200 rounded-xl p-10 text-center flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <h4 class="font-black text-red-700 uppercase mb-2">Akses Ditolak</h4>
                                        <p class="text-xs font-bold text-red-600 mb-6 max-w-sm">Jalur ini diperuntukkan khusus bagi pendaftaran atlet resmi tingkat provinsi/kabupaten. Anda harus memiliki dan login menggunakan akun berstatus Kontingen.</p>
                                        <a href="{{ url('/login') }}" class="bg-red-600 text-white px-6 py-2.5 rounded font-black text-xs uppercase hover:bg-red-700 shadow-md">LOGIN AKUN KONTINGEN</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- SCRIPT PENGENDALI TAB TOGGLE -->
    <script>
        function switchTab(jalur) {
            const tabOpen = document.getElementById('tab-open');
            const tabKejurnas = document.getElementById('tab-kejurnas');
            const btnOpen = document.getElementById('btn-open');
            const btnKejurnas = document.getElementById('btn-kejurnas');

            const activeClass = "flex-1 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-yellow text-navy shadow-md";
            const inactiveClass = "flex-1 py-3.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-white/50 hover:text-white";

            if (jalur === 'open' && tabOpen) {
                tabOpen.classList.replace('hidden', 'block');
                if (tabKejurnas) tabKejurnas.classList.replace('block', 'hidden');
                if (btnOpen) btnOpen.className = activeClass;
                if (btnKejurnas) btnKejurnas.className = inactiveClass;
            } else if (jalur === 'kejurnas' && tabKejurnas) {
                tabKejurnas.classList.replace('hidden', 'block');
                if (tabOpen) tabOpen.classList.replace('block', 'hidden');
                if (btnKejurnas) btnKejurnas.className = activeClass;
                if (btnOpen) btnOpen.className = inactiveClass;
            }
        }
    </script>
@endsection
