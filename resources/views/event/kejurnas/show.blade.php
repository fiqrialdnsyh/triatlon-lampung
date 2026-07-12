@extends('layouts.main')

@section('title', $event->judul . ' - FTI LAMPUNG')

@section('content')
    <section class="bg-[#F8F9FA] py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <a href="{{ route('main_event.index') }}"
                class="inline-flex items-center text-navy/50 hover:text-navy font-bold text-xs uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Katalog event
            </a>

            @if (session('success'))
                <div
                    class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- STRUKTUR TAMPILAN KHUSUS LEVEL ADMINISTRATOR              --}}
            {{-- ========================================================= --}}
            @if (auth()->check() && auth()->user()->email === 'admin@triatlon.test')
                <div class="space-y-6 mb-10">

                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                            <div>
                                <h3 class="font-black text-navy uppercase text-base">Dasbor Validasi Lapangan (Check-In)
                                </h3>
                                <p class="text-[11px] text-navy/50 font-bold mt-0.5">Gunakan kamera untuk memindai tiket
                                    peserta. Data kehadiran akan dikelompokkan per kontingen.</p>
                            </div>
                            <div class="flex gap-2 w-full md:w-auto">
                                <a href="{{ route('event.kejurnas.checkin.print', $event->id) }}" target="_blank"
                                    class="flex-1 md:flex-none text-center bg-gray-50 text-navy hover:bg-gray-100 border border-gray-300 px-4 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-sm transition-colors">
                                    Cetak PDF
                                </a>
                                <button onclick="openScannerModal()"
                                    class="flex-1 md:flex-none w-full md:w-auto bg-navy text-white hover:bg-yellow hover:text-navy border border-navy/20 px-6 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-md transition-colors cursor-pointer">
                                    [SCAN QR TIKET]
                                </button>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 rounded-xl border border-gray-200 overflow-hidden">
                            @php
                                $groupedCheckins = $allRegistrations->whereNotNull('waktu_checkin')->groupBy('user_id');
                            @endphp

                            @forelse($groupedCheckins as $userId => $checkins)
                                @php
                                    $namaKontingen = $checkins->first()->user->name ?? 'Kontingen Tidak Diketahui';
                                    $checkinsPerNomor = $checkins->groupBy('kategori_lomba');
                                @endphp

                                <div class="bg-navy p-3 flex justify-between items-center border-b border-navy/10">
                                    <h4 class="font-black text-white uppercase text-xs tracking-wider">DELEGASI HADIR:
                                        {{ $namaKontingen }}</h4>
                                    <span
                                        class="bg-yellow text-navy px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-widest">{{ $checkins->count() }}
                                        Atlet</span>
                                </div>

                                @foreach ($checkinsPerNomor as $nomorLomba => $checkinsNomor)
                                    <div class="bg-white px-4 py-2 border-b border-gray-100 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow"></span>
                                        <p class="text-[10px] font-black text-navy uppercase tracking-widest">
                                            {{ $nomorLomba }}</p>
                                        <span class="text-[9px] font-bold text-navy/40">({{ $checkinsNomor->count() }}
                                            Atlet)</span>
                                    </div>

                                    <div class="overflow-x-auto mb-2">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr
                                                    class="bg-gray-100 text-navy uppercase font-black text-[9px] tracking-widest border-b border-gray-200">
                                                    <th class="p-3 w-10 text-center">No</th>
                                                    <th class="p-3">Nama Atlet</th>
                                                    <th class="p-3 text-center">Gender & Gol. Darah</th>
                                                    <th class="p-3 text-center">BIB</th>
                                                    <th class="p-3 text-right">Waktu Hadir</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @php $no = 1; @endphp
                                                @foreach ($checkinsNomor as $checkin)
                                                    <tr class="hover:bg-white transition-colors">
                                                        <td class="p-3 text-center font-bold text-gray-400">
                                                            {{ $no++ }}</td>
                                                        <td class="p-3">
                                                            <p class="font-black text-navy uppercase text-[11px]">
                                                                {{ $checkin->nama_lengkap }}</p>
                                                            <p class="text-[9px] text-gray-500 uppercase">
                                                                {{ $checkin->asal_daerah }}</p>
                                                        </td>
                                                        <td class="p-3 text-center">
                                                            <p class="font-bold text-navy text-[10px] uppercase">
                                                                {{ $checkin->jenis_kelamin }}</p>
                                                            <p class="text-[9px] text-red-600 font-black tracking-widest">
                                                                {{ $checkin->golongan_darah }}</p>
                                                        </td>
                                                        <td class="p-3 text-center font-black text-navy">
                                                            {{ $checkin->bib_name }}</td>
                                                        <td
                                                            class="p-3 text-right font-bold text-green-600 font-mono text-[10px]">
                                                            {{ \Carbon\Carbon::parse($checkin->waktu_checkin)->format('d/m/Y - H:i:s') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @empty
                                <div class="p-6 text-center text-navy/40 font-bold uppercase text-[10px] tracking-widest">
                                    Belum ada peserta yang melakukan Check-In dari kontingen manapun.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="font-black text-navy uppercase text-sm border-b border-gray-200 pb-2">Verifikasi Berkas
                            Registrasi per Kontingen</h3>

                        @forelse($groupedRegistrations as $userId => $registrations)
                            @php
                                $kontingenName =
                                    $registrations->first()->user->name ?? 'Kontingen Dihapus / Tidak Diketahui';
                                $registrationsPerNomor = $registrations->groupBy('kategori_lomba');
                            @endphp

                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                                <div class="bg-navy p-4 flex justify-between items-center border-b border-gray-200">
                                    <h3 class="font-black text-white uppercase text-sm flex items-center gap-2">
                                        DELEGASI: {{ $kontingenName }}
                                    </h3>
                                    <span
                                        class="bg-yellow text-navy px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ $registrations->count() }} Atlet Terdaftar
                                    </span>
                                </div>

                                @foreach ($registrationsPerNomor as $nomorLomba => $regsNomor)
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-navy"></span>
                                        <p class="text-[10px] font-black text-navy uppercase tracking-widest">
                                            {{ $nomorLomba }}</p>
                                        <span class="text-[9px] font-bold text-navy/40">({{ $regsNomor->count() }}
                                            Atlet)</span>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs border-collapse min-w-[900px]">
                                            <thead>
                                                <tr
                                                    class="bg-gray-50 text-navy uppercase font-bold text-[10px] tracking-wider border-b border-gray-200">
                                                    <th class="p-4 rounded-l-md w-[45%]">Data Lengkap Profil Atlet</th>
                                                    <th class="p-4 w-[20%]">Golongan Biaya & Tarif</th>
                                                    <th class="p-4 text-center w-[15%]">Bukti Transaksi</th>
                                                    <th class="p-4 text-center rounded-r-md w-[20%]">Aksi Validasi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y-2 divide-gray-100 font-semibold text-navy">
                                                @foreach ($regsNomor as $reg)
                                                    <tr
                                                        class="odd:bg-white even:bg-gray-50 hover:bg-blue-50/40 transition-colors">
                                                        <td class="p-4">
                                                            <div class="mb-2 border-b border-gray-200/50 pb-2">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <p
                                                                        class="font-black uppercase text-sm text-navy tracking-wide">
                                                                        {{ $reg->nama_lengkap }}</p>
                                                                    <span
                                                                        class="bg-navy text-yellow px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest">
                                                                        BIB: {{ $reg->bib_name }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                                                                <div>
                                                                    <p
                                                                        class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                        NIK KTP</p>
                                                                    <p class="text-[11px] font-semibold text-navy">
                                                                        {{ $reg->nomor_ktp }}</p>
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                        TTL & Usia</p>
                                                                    <p class="text-[11px] font-semibold text-navy">
                                                                        {{ \Carbon\Carbon::parse($reg->tanggal_lahir)->format('d/m/Y') }}
                                                                        ({{ $reg->usia }} Thn)</p>
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                        Jenis Kelamin</p>
                                                                    <p class="text-[11px] font-black text-navy uppercase">
                                                                        {{ $reg->jenis_kelamin }}</p>
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                        Gol. Darah</p>
                                                                    <p
                                                                        class="text-[11px] font-black text-red-600 uppercase">
                                                                        {{ $reg->golongan_darah }}</p>
                                                                </div>
                                                                <div class="col-span-2">
                                                                    <p
                                                                        class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                        Daerah Domisili</p>
                                                                    <p
                                                                        class="text-[11px] font-semibold text-navy uppercase">
                                                                        {{ $reg->asal_daerah }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="p-4 align-top pt-5 border-l border-gray-100">
                                                            <span
                                                                class="inline-block bg-white text-gray-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider mb-2 border border-gray-200">
                                                                {{ $reg->golongan_biaya }}
                                                            </span>
                                                            <p class="text-xs font-black text-green-600">Rp
                                                                {{ number_format($reg->nominal_bayar, 0, ',', '.') }}</p>
                                                        </td>
                                                        <td class="p-4 text-center align-top pt-5 border-l border-gray-100">
                                                            <button type="button"
                                                                data-url="{{ asset($reg->bukti_transfer) }}"
                                                                onclick="openProofModal(this.dataset.url)"
                                                                class="inline-flex items-center justify-center gap-1 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 px-3 py-1.5 rounded text-[9px] font-black uppercase tracking-wider transition-all duration-300">
                                                                Cek Bukti
                                                            </button>
                                                        </td>
                                                        <td class="p-4 align-top pt-5 border-l border-gray-100">
                                                            @if ($reg->status_pembayaran == 'Menunggu')
                                                                <form
                                                                    action="{{ route('event.kejurnas.verifikasi', $reg->id) }}"
                                                                    method="POST" class="flex flex-col items-end gap-2">
                                                                    @csrf
                                                                    <input type="text" name="pesan_penolakan"
                                                                        placeholder="Alasan jika ditolak..."
                                                                        class="bg-white border border-gray-300 rounded px-2 py-1.5 text-[10px] w-full font-semibold focus:outline-none focus:border-navy">
                                                                    <div class="flex gap-1.5 w-full">
                                                                        <button type="submit" name="status_pembayaran"
                                                                            value="Valid"
                                                                            class="w-1/2 bg-green-500 hover:bg-green-600 text-white py-1.5 rounded text-[9px] uppercase font-black transition-colors cursor-pointer">Terima</button>
                                                                        <button type="submit" name="status_pembayaran"
                                                                            value="Ditolak"
                                                                            class="w-1/2 bg-red-500 hover:bg-red-600 text-white py-1.5 rounded text-[9px] uppercase font-black transition-colors cursor-pointer">Tolak</button>
                                                                    </div>
                                                                </form>
                                                            @else
                                                                <div class="flex flex-col items-end justify-start gap-1">
                                                                    <span
                                                                        class="px-4 py-1.5 rounded text-[9px] font-black uppercase tracking-widest block w-full text-center border {{ $reg->status_pembayaran == 'Valid' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                                                        STATUS: {{ $reg->status_pembayaran }}
                                                                    </span>
                                                                    <form
                                                                        action="{{ route('event.kejurnas.verifikasi', $reg->id) }}"
                                                                        method="POST" class="w-full text-right mt-1">
                                                                        @csrf
                                                                        <button type="submit" name="status_pembayaran"
                                                                            value="Menunggu"
                                                                            class="text-[9px] text-navy/40 font-bold hover:text-navy hover:underline cursor-pointer w-full text-right">Batalkan
                                                                            (Reset)</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="bg-white border-2 border-dashed border-gray-300 p-12 rounded-2xl text-center">
                                <p class="font-black text-navy/40 uppercase text-sm tracking-wider">Belum ada satupun
                                    kontingen yang mendaftarkan atlet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- STRUKTUR TAMPILAN UNTUK NON-ADMIN (GUEST / KONTINGEN)     --}}
                {{-- ========================================================= --}}
            @else
                <div class="flex flex-col lg:flex-row gap-8">

                    {{-- INFO EVENT — SELALU TAMPIL UNTUK SIAPA SAJA, TERMASUK GUEST --}}
                    <div class="w-full lg:w-4/12 space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                            @if ($event->poster)
                                <img src="{{ asset($event->poster) }}" alt="Poster"
                                    class="w-full rounded-xl object-cover mb-6">
                            @endif

                            <h1 class="font-black text-navy text-2xl uppercase leading-tight mb-4">{{ $event->judul }}
                            </h1>

                            <div class="grid grid-cols-2 gap-3 mb-6">
                                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl text-center">
                                    <p class="text-[9px] font-black text-navy/40 uppercase">Sisa Kuota Total</p>
                                    <p class="text-lg font-black text-navy">
                                        {{ max(0, $event->kuota_maksimal - $kuotaTerisi) }} / {{ $event->kuota_maksimal }}
                                    </p>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl text-center">
                                    <p class="text-[9px] font-black text-navy/40 uppercase">Batas Penutupan</p>
                                    <p class="text-xs font-black text-red-500 mt-1 uppercase">
                                        {{ \Carbon\Carbon::parse($event->batas_pendaftaran)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-[10px] font-black text-navy uppercase tracking-widest mb-1">Deskripsi
                                    Kejuaraan</h4>
                                <p class="text-xs font-semibold text-navy/70 leading-relaxed text-justify">
                                    {{ $event->deskripsi }}</p>
                            </div>

                            <div class="mb-6 text-xs font-semibold text-navy/70">
                                <p><span class="font-black text-navy uppercase text-[10px] block mb-0.5">Lokasi</span>
                                    {{ $event->lokasi }}</p>
                                <p class="mt-2"><span
                                        class="font-black text-navy uppercase text-[10px] block mb-0.5">Kategori
                                        Lomba</span> {{ $event->kategori_lomba }}</p>
                            </div>

                            @if ($event->thb_file)
                                <a href="{{ asset($event->thb_file) }}" target="_blank"
                                    class="w-full bg-yellow text-navy px-4 py-3.5 rounded-xl font-black text-xs uppercase tracking-wider text-center block mb-6 shadow-sm border border-yellow/20">
                                    Download Technical Handbook
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- BLOK PENDAFTARAN — DI SINI BARU DIBEDAKAN BERDASARKAN ROLE --}}
                    <div class="w-full lg:w-8/12">
                        @auth
                            @if (auth()->user()->role === 'kontingen')
                                <div class="bg-cream p-8 rounded-2xl border border-gray-200 shadow-sm mb-8">
                                    <div class="mb-6 bg-white p-5 rounded-xl border-l-4 border-yellow shadow-sm">
                                        <h4 class="font-black text-navy uppercase text-xs tracking-wider mb-2">Tujuan Rekening
                                            Transaksi Kontingen</h4>
                                        <p class="text-[11px] font-bold text-navy/70 uppercase"><strong
                                                class="text-navy">{{ $event->nama_bank }}</strong> |
                                            {{ $event->nomor_rekening }} | A.N {{ $event->atas_nama }}</p>
                                    </div>

                                    <h3 class="font-oswald text-2xl font-bold uppercase text-navy tracking-wide mb-2">
                                        Pendaftaran Atlet Kontingen</h3>
                                    <p class="text-xs text-navy/50 font-bold mb-6">Formulir ini dapat digunakan berulang kali
                                        untuk memasukkan seluruh data atlet yang berada di bawah naungan Anda.</p>

                                    @if ($errors->any())
                                        <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl">
                                            <p class="text-xs font-black text-red-700 uppercase mb-2">Gagal Mengirim Berkas,
                                                Periksa Kembali Isian Anda:</p>
                                            <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('event.kejurnas.register', $event->id) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                            <h4
                                                class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">
                                                Biodata Pribadi Atlet</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama
                                                        Lengkap Sesuai ID</label>
                                                    <input type="text" name="nama_lengkap"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nomor
                                                        Induk Kependudukan (KTP / KIA)</label>
                                                    <input type="text" name="nomor_ktp" inputmode="numeric"
                                                        pattern="[0-9]*" maxlength="16" minlength="16"
                                                        placeholder="Masukkan 16 digit NIK"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tempat
                                                        Lahir</label>
                                                    <input type="text" name="tempat_lahir"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tanggal
                                                        Lahir <span class="text-gray-400 font-medium">(Usia dihitung
                                                            otomatis)</span></label>
                                                    <input type="date" name="tanggal_lahir"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Jenis
                                                        Kelamin</label>
                                                    <select name="jenis_kelamin"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                        <option value="Putra">Putra</option>
                                                        <option value="Putri">Putri</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Golongan
                                                        Darah</label>
                                                    <select name="golongan_darah"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                        <option value="A+">A Positif (A+)</option>
                                                        <option value="A-">A Negatif (A-)</option>
                                                        <option value="B+">B Positif (B+)</option>
                                                        <option value="B-">B Negatif (B-)</option>
                                                        <option value="AB+">AB Positif (AB+)</option>
                                                        <option value="AB-">AB Negatif (AB-)</option>
                                                        <option value="O+">O Positif (O+)</option>
                                                        <option value="O-">O Negatif (O-)</option>
                                                        <option value="Tidak Tahu">Tidak Tahu</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Asal
                                                        Daerah (Kab/Kota & Provinsi)</label>
                                                    <input type="text" name="asal_daerah"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy"
                                                        required>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Alamat
                                                        Lengkap</label>
                                                    <textarea name="alamat" rows="2"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy"
                                                        required></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                            <h4
                                                class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">
                                                Informasi Kontak & Perlombaan Atlet</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Email
                                                        Atlet</label>
                                                    <input type="email" name="email"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none"
                                                        required>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">No
                                                        WhatsApp Atlet</label>
                                                    <input type="text" name="nomor_telepon"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none"
                                                        required>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama
                                                        BIB / Name Tag (Maks. 15 Huruf)</label>
                                                    <input type="text" name="bib_name" maxlength="15"
                                                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-black text-navy uppercase focus:outline-none"
                                                        required>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Kategori
                                                        Lomba (Ditetapkan Panitia)</label>
                                                    <select name="kategori_lomba"
                                                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold text-navy focus:outline-none shadow-sm pointer-events-none"
                                                        required>
                                                        <option value="{{ $event->kategori_lomba }}" selected>
                                                            {{ $event->kategori_lomba }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <h4
                                                class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">
                                                Pilihan Tarif Registrasi & Bukti</h4>
                                            <div class="space-y-2 mb-4">
                                                @php $skemas = json_decode($event->skema_biaya, true) ?? []; @endphp
                                                @foreach ($skemas as $s)
                                                    <label
                                                        class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-navy transition-colors">
                                                        <input type="radio" name="golongan_biaya"
                                                            value="{{ $s['nama'] }}"
                                                            class="w-4 h-4 text-navy focus:ring-navy cursor-pointer" required>
                                                        <span class="text-xs font-bold text-navy">{{ $s['nama'] }} <span
                                                                class="text-gray-400 font-medium ml-1"> (Rp
                                                                {{ number_format($s['biaya'], 0, ',', '.') }})</span></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <label
                                                class="block text-[10px] font-black text-navy uppercase tracking-widest mb-2 mt-4">Unggah
                                                Resi Pembayaran Untuk Atlet Ini</label>
                                            <input type="file" name="bukti_transfer" accept="image/*"
                                                class="w-full text-xs text-navy file:mr-3 file:py-1.5 file:px-3 file:bg-navy file:text-yellow file:border-0 border border-gray-300 rounded p-0.5 bg-white cursor-pointer"
                                                required>
                                        </div>

                                        <button type="submit"
                                            class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-xs uppercase tracking-wider transition-colors shadow-md mt-4 hover:bg-navy/90">
                                            + Daftarkan Atlet Ini
                                        </button>
                                    </form>
                                </div>

                                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                                    <h3 class="font-black text-navy uppercase text-sm mb-4 pb-2 border-b border-gray-100">
                                        Daftar Atlet Delegasi Anda</h3>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-[11px] border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100 text-navy uppercase font-black tracking-wider">
                                                    <th class="p-3 w-10 text-center">No</th>
                                                    <th class="p-3">Nama & BIB</th>
                                                    <th class="p-3">Asal Daerah</th>
                                                    <th class="p-3 text-center">Status Verifikasi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 font-semibold text-navy/80">
                                                @php $no_urut = 1; @endphp
                                                @forelse($myAtletRegistrations as $atlet)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="p-3 text-center">{{ $no_urut++ }}</td>
                                                        <td class="p-3">
                                                            <p class="font-black uppercase text-navy">
                                                                {{ $atlet->nama_lengkap }}</p>
                                                            <p class="text-[9px] text-gray-500 uppercase mt-0.5">BIB:
                                                                {{ $atlet->bib_name }}</p>
                                                        </td>
                                                        <td class="p-3 uppercase">{{ $atlet->asal_daerah }}</td>
                                                        <td class="p-3 text-center">
                                                            <span
                                                                class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-wider
                                                                {{ $atlet->status_pembayaran == 'Valid' ? 'bg-green-100 text-green-700' : ($atlet->status_pembayaran == 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow/20 text-yellow-700 border border-yellow/30') }}">
                                                                {{ $atlet->status_pembayaran }}
                                                            </span>
                                                            @if ($atlet->status_pembayaran == 'Ditolak' && $atlet->pesan_penolakan)
                                                                <p class="text-[9px] text-red-500 mt-1 font-bold">Alasan:
                                                                    {{ $atlet->pesan_penolakan }}</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4"
                                                            class="p-4 text-center font-bold text-gray-400 uppercase tracking-widest text-[10px]">
                                                            Anda belum mendaftarkan atlet satupun.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                {{-- User login TAPI bukan kontingen (bukan admin, sudah ditangani di atas) --}}
                                <div class="bg-gray-50 border border-gray-200 p-8 rounded-2xl text-center shadow-sm">
                                    <h3 class="font-black text-navy text-xl uppercase mb-2">Pendaftaran Khusus Kontingen</h3>
                                    <p class="text-xs font-bold text-navy/50">Akun Anda belum terdaftar sebagai Akun Kontingen
                                        resmi. Hubungi admin untuk pembuatan akun kontingen jika Anda mewakili suatu
                                        daerah/provinsi.</p>
                                </div>
                            @endif
                        @else
                            {{-- GUEST: belum login sama sekali --}}
                            <div class="bg-gray-50 border border-gray-200 p-8 rounded-2xl text-center shadow-sm">
                                <h3 class="font-black text-navy text-xl uppercase mb-2">Pendaftaran Tertutup</h3>
                                <p class="text-xs font-bold text-navy/50 mb-6">Silakan masuk menggunakan Akun Kontingen untuk
                                    mendaftarkan atlet delegasi Anda.</p>
                                <a href="{{ url('/login') }}"
                                    class="inline-block bg-navy text-yellow px-6 py-2.5 rounded font-black text-xs uppercase tracking-wider transition-colors shadow-md hover:bg-navy/90">
                                    Login Sebagai Kontingen
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- POPUP MODAL BUKTI TRANSFER (SATU-SATUNYA, TIDAK DUPLIKAT) --}}
    <div id="proofModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeProofModal()"></div>
        <div
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden max-h-[85vh] animate-fadeIn">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 shrink-0">
                <h3 class="font-black text-navy uppercase text-xs tracking-wider">Lembar Bukti Resi Pembayaran</h3>
                <button onclick="closeProofModal()"
                    class="text-red-500 font-black text-xs uppercase hover:underline cursor-pointer">Tutup</button>
            </div>
            <div class="p-6 flex-1 overflow-y-auto bg-gray-100 flex items-center justify-center">
                <img id="proofImage" src="" alt="Bukti Transfer"
                    class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-md border border-gray-200">
            </div>
        </div>
    </div>

    @if (auth()->check() && auth()->user()->email === 'admin@triatlon.test')
        <div id="scannerModal"
            class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-navy/90 backdrop-blur-sm">
            <div
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden max-h-[90vh] animate-fadeIn">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-navy text-white shrink-0">
                    <h3 class="font-black uppercase text-sm tracking-wider">Pemindai Tiket Kehadiran</h3>
                    <button onclick="closeScannerModal()"
                        class="text-white/50 hover:text-white font-black text-sm uppercase cursor-pointer">Tutup</button>
                </div>
                <div class="p-4 flex flex-col items-center justify-start bg-gray-50 overflow-y-auto">
                    <div id="reader" class="w-full max-w-sm overflow-hidden rounded-xl shadow-inner bg-black"></div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-4 text-center">Arahkan
                        Kamera ke QR Code Peserta</p>
                    <div id="scanResult" class="w-full mt-4 hidden rounded-xl p-4 border-2"></div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            let html5QrcodeScanner;
            let requiresReload = false;

            function openScannerModal() {
                document.getElementById('scannerModal').classList.remove('hidden');
                document.getElementById('scannerModal').classList.add('flex');
                document.body.style.overflow = 'hidden';
                document.getElementById('scanResult').classList.add('hidden');
                requiresReload = false;

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", {
                        fps: 30,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                        disableFlip: false
                    },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }

            function closeScannerModal() {
                document.getElementById('scannerModal').classList.replace('flex', 'hidden');
                document.body.style.overflow = 'auto';
                if (html5QrcodeScanner) html5QrcodeScanner.clear();
                if (requiresReload) window.location.reload();
            }

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.pause();
                const resultBox = document.getElementById('scanResult');
                resultBox.classList.remove('hidden');
                resultBox.innerHTML =
                    `<p class="text-center text-xs font-bold text-navy animate-pulse">Menghubungkan ke Database...</p>`;

                fetch("{{ route('event.kejurnas.checkin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            qr_token: decodedText,
                            event_id: "{{ $event->id }}"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            requiresReload = true;
                            resultBox.className = "w-full mt-4 rounded-xl p-4 border-2 bg-green-50 border-green-400";
                            resultBox.innerHTML = `
                            <div class="text-center">
                                <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h4 class="font-black text-green-700 uppercase text-lg">CHECK-IN BERHASIL</h4>
                                <p class="text-[10px] font-bold text-green-600/70 uppercase tracking-widest mb-4">Akses Pertandingan Dibuka</p>
                                <div class="bg-white rounded-lg p-3 text-left shadow-sm border border-green-200">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Nama Atlet</p>
                                    <p class="font-black text-navy uppercase text-sm mb-1">${data.data.nama}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">Kategori & BIB</p>
                                    <p class="font-black text-navy uppercase text-xs mb-1">${data.data.kategori} <span class="bg-navy text-yellow px-1.5 rounded ml-1">BIB: ${data.data.bib}</span></p>
                                </div>
                                <button onclick="closeScannerModal()" class="mt-4 bg-navy text-yellow px-4 py-3 w-full text-xs font-black uppercase rounded-lg shadow-md cursor-pointer hover:bg-yellow hover:text-navy transition-colors">Tutup & Perbarui Tabel Hadir</button>
                            </div>
                        `;
                        } else {
                            resultBox.className = "w-full mt-4 rounded-xl p-4 border-2 bg-red-50 border-red-400";
                            resultBox.innerHTML = `
                            <div class="text-center">
                                <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-md">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                                <h4 class="font-black text-red-700 uppercase text-sm mb-2">GAGAL VALIDASI ABSENSI</h4>
                                <p class="text-xs font-bold text-red-600 leading-relaxed px-2 mb-4">${data.message}</p>
                                <button onclick="resumeScanner()" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 text-[10px] font-black uppercase rounded-lg cursor-pointer shadow-sm">Scan Tiket Lain</button>
                            </div>
                        `;
                        }
                    })
                    .catch(error => {
                        resultBox.className = "w-full mt-4 rounded-xl p-4 border-2 bg-red-50 border-red-400";
                        resultBox.innerHTML =
                            `<p class="text-xs font-bold text-red-600 text-center">Terjadi kesalahan koneksi server.</p><button onclick="resumeScanner()" class="mt-4 bg-red-500 text-white px-4 py-2 text-[10px] font-black uppercase rounded-lg w-full">Coba Lagi</button>`;
                    });
            }

            function resumeScanner() {
                document.getElementById('scanResult').classList.add('hidden');
                html5QrcodeScanner.resume();
            }

            function onScanFailure(error) {}
        </script>
    @endif

    <script>
        function openProofModal(imageUrl) {
            document.getElementById('proofImage').src = imageUrl;
            document.getElementById('proofModal').classList.remove('hidden');
            document.getElementById('proofModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.replace('flex', 'hidden');
            document.getElementById('proofImage').src = '';
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
