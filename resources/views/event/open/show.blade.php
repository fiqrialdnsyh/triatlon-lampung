@extends('layouts.main')

@section('title', $event->judul . ' - FTI LAMPUNG')

@section('content')
    <section class="bg-[#F8F9FA] py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <a href="{{ route('event.open.index') }}" class="inline-flex items-center text-navy/50 hover:text-navy font-bold text-xs uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog Event
            </a>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ========================================================= -->
            <!-- STRUKTUR TAMPILAN KHUSUS LEVEL ADMINISTRATOR              -->
            <!-- ========================================================= -->
            @auth
                @if(auth()->user()->email === 'admin@triatlon.test')
                    <div class="space-y-6 mb-10">
                        <!-- PANEL QR CHECK-IN ATLET STYLE NAVBAR -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                                <div>
                                    <h3 class="font-black text-navy uppercase text-base">Dasbor Validasi Lapangan (Check-In)</h3>
                                    <p class="text-[11px] text-navy/50 font-bold mt-0.5">Gunakan kamera untuk memindai tiket peserta. Data yang masuk akan tampil di bawah.</p>
                                </div>
                                <div class="flex gap-2 w-full md:w-auto">
                                    <a href="{{ route('event.open.checkin.print', $event->id) }}" target="_blank" class="flex-1 md:flex-none text-center bg-gray-50 text-navy hover:bg-gray-100 border border-gray-300 px-4 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-sm transition-colors">
                                        Cetak PDF
                                    </a>
                                    <button onclick="openScannerModal()" class="flex-1 md:flex-none w-full md:w-auto bg-navy text-white hover:bg-yellow hover:text-navy border border-navy/20 px-6 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-md transition-colors cursor-pointer">
                                        [SCAN QR TIKET]
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto bg-gray-50/50 rounded-xl border border-gray-200">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 text-navy uppercase font-black text-[9px] tracking-widest border-b border-gray-200">
                                            <th class="p-3 w-10 text-center">No</th>
                                            <th class="p-3">Nama Atlet</th>
                                            <th class="p-3 text-center">BIB</th>
                                            <th class="p-3">Kategori Lomba</th>
                                            <th class="p-3 text-right">Waktu Hadir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @php $no = 1; @endphp
                                        @forelse($allRegistrations->whereNotNull('waktu_checkin') as $checkin)
                                            <tr class="hover:bg-white transition-colors">
                                                <td class="p-3 text-center font-bold text-gray-400">{{ $no++ }}</td>
                                                <td class="p-3">
                                                    <p class="font-black text-navy uppercase text-[11px]">{{ $checkin->nama_lengkap }}</p>
                                                    <p class="text-[9px] text-gray-500 uppercase">{{ $checkin->asal_daerah }}</p>
                                                </td>
                                                <td class="p-3 text-center font-black text-navy">{{ $checkin->bib_name }}</td>
                                                <td class="p-3 font-semibold text-navy/70 text-[10px]">{{ $checkin->kategori_lomba }}</td>
                                                <td class="p-3 text-right font-bold text-green-600 font-mono text-[10px]">{{ \Carbon\Carbon::parse($checkin->waktu_checkin)->format('d/m/Y - H:i:s') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-6 text-center text-navy/40 font-bold uppercase text-[10px] tracking-widest">Belum ada peserta yang melakukan Check-In.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TABEL MONITORING & VERIFIKASI PESERTA -->
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 overflow-hidden">
                            <h3 class="font-black text-navy uppercase text-sm mb-4 pb-2 border-b border-gray-100">Daftar Pengajuan Registrasi Peserta</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse min-w-[900px]">
                                    <thead>
                                        <tr class="bg-navy text-white uppercase font-bold">
                                            <th class="p-3 rounded-l-md w-[45%]">Data Lengkap Profil Atlet</th>
                                            <th class="p-3 w-[20%]">Kategori & Tarif</th>
                                            <th class="p-3 text-center w-[15%]">Bukti Transaksi</th>
                                            <th class="p-3 text-center rounded-r-md w-[20%]">Aksi Konfirmasi Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y-2 divide-gray-200 font-semibold text-navy">
                                        @forelse($allRegistrations as $reg)
                                            <tr class="odd:bg-white even:bg-gray-100 hover:bg-blue-50/50 transition-colors group">

                                                <td class="p-5">
                                                    <div class="mb-3 border-b border-gray-200/60 pb-3">
                                                        <div class="flex items-center gap-3 mb-1.5">
                                                            <p class="font-black uppercase text-base text-navy tracking-wide">{{ $reg->nama_lengkap }}</p>
                                                            <span class="bg-navy text-yellow px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest shadow-sm border border-navy/20">
                                                                BIB: {{ $reg->bib_name }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-3 text-[10px] font-semibold text-navy/50">
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-3 h-3 text-navy/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                                {{ $reg->email }}
                                                            </span>
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-3 h-3 text-navy/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                                {{ $reg->nomor_telepon }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                                                        <div>
                                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">NIK KTP</p>
                                                            <p class="text-xs font-semibold text-navy">{{ $reg->nomor_ktp }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">TTL & Usia</p>
                                                            <p class="text-xs font-semibold text-navy">{{ $reg->tempat_lahir }}, {{ \Carbon\Carbon::parse($reg->tanggal_lahir)->format('d/m/Y') }} <span class="text-navy/50">({{ $reg->usia }} Thn)</span></p>
                                                        </div>
                                                        <div>
                                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Gender & Gol. Darah</p>
                                                            <p class="text-xs font-semibold text-navy">{{ $reg->jenis_kelamin }} <span class="text-gray-300 mx-1">|</span> {{ $reg->golongan_darah }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Daerah Domisili</p>
                                                            <p class="text-xs font-semibold text-navy uppercase">{{ $reg->asal_daerah }}</p>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Alamat Lengkap</p>
                                                            <p class="text-xs font-semibold text-navy line-clamp-1" title="{{ $reg->alamat }}">{{ $reg->alamat }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="p-5 align-top pt-6 border-l border-gray-100">
                                                    <p class="font-black text-sm text-navy uppercase leading-tight mb-1.5">{{ $reg->kategori_lomba }}</p>
                                                    <span class="inline-block bg-white text-gray-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider mb-3 border border-gray-200 shadow-sm">
                                                        {{ $reg->golongan_biaya }}
                                                    </span>
                                                    <p class="text-sm font-black text-green-600 tracking-wide">Rp {{ number_format($reg->nominal_bayar, 0, ',', '.') }}</p>
                                                </td>

                                                <td class="p-5 text-center align-top pt-6 border-l border-gray-100">
                                                    <button type="button" onclick="openProofModal('{{ asset($reg->bukti_transfer) }}')" class="inline-flex items-center justify-center gap-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all duration-300 shadow-sm">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        Cek Bukti
                                                    </button>
                                                </td>

                                                <td class="p-5 align-top pt-6 border-l border-gray-100">
                                                    @if($reg->status_pembayaran == 'Menunggu')
                                                        <form action="{{ route('event.open.verifikasi', $reg->id) }}" method="POST" class="flex flex-col items-end gap-2.5">
                                                            @csrf
                                                            <input type="text" name="pesan_penolakan" placeholder="Alasan jika ditolak..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-[10px] w-full font-semibold focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy shadow-sm transition-all">
                                                            <div class="flex gap-2 w-full">
                                                                <button type="submit" name="status_pembayaran" value="Valid" class="w-1/2 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-[10px] uppercase font-black transition-colors shadow-sm cursor-pointer text-center tracking-wider">Terima</button>
                                                                <button type="submit" name="status_pembayaran" value="Ditolak" class="w-1/2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-[10px] uppercase font-black transition-colors shadow-sm cursor-pointer text-center tracking-wider">Tolak</button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <div class="flex flex-col items-end justify-start gap-1">
                                                            <span class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest block w-full text-center border
                                                                {{ $reg->status_pembayaran == 'Valid' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                                                STATUS: {{ $reg->status_pembayaran }}
                                                            </span>

                                                            @if($reg->status_pembayaran == 'Ditolak' && $reg->pesan_penolakan)
                                                                <p class="text-[10px] text-red-500/90 font-bold w-full text-right leading-snug mt-1.5" title="{{ $reg->pesan_penolakan }}">Alasan: "{{ $reg->pesan_penolakan }}"</p>
                                                            @endif

                                                            <form action="{{ route('event.open.verifikasi', $reg->id) }}" method="POST" class="w-full text-right mt-1.5">
                                                                @csrf
                                                                <button type="submit" name="status_pembayaran" value="Menunggu" class="text-[10px] text-navy/40 font-bold hover:text-navy hover:underline transition-colors cursor-pointer inline-flex items-center justify-end w-full gap-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                                    Batalkan (Reset)
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-6 text-center text-navy/40 uppercase font-bold">Belum ada atlet yang mengajukan berkas pendaftaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- ========================================================= -->
            <!-- STRUKTUR TAMPILAN LEVEL PUBLIK / KANDIDAT ATLET           -->
            <!-- ========================================================= -->
            @if(!auth()->check() || auth()->user()->email !== 'admin@triatlon.test')
                <div class="flex flex-col lg:flex-row gap-8">

                    <!-- INFORMASI EVENT -->
                    <div class="w-full lg:w-5/12 space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                            @if($event->poster)
                                <img src="{{ asset($event->poster) }}" alt="Poster" class="w-full rounded-xl object-cover mb-6">
                            @endif

                            <h1 class="font-black text-navy text-2xl uppercase leading-tight mb-4">{{ $event->judul }}</h1>

                            <div class="grid grid-cols-2 gap-3 mb-6">
                                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl text-center">
                                    <p class="text-[9px] font-black text-navy/40 uppercase">Sisa Kuota</p>
                                    <p class="text-lg font-black text-navy">{{ max(0, $event->kuota_maksimal - $kuotaTerisi) }} / {{ $event->kuota_maksimal }}</p>
                                </div>
                                <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl text-center">
                                    <p class="text-[9px] font-black text-navy/40 uppercase">Batas Penutupan</p>
                                    <p class="text-xs font-black text-red-500 mt-1 uppercase">{{ \Carbon\Carbon::parse($event->batas_pendaftaran)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            @if($event->thb_file)
                                <a href="{{ asset($event->thb_file) }}" target="_blank" class="w-full bg-yellow text-navy px-4 py-3.5 rounded-xl font-black text-xs uppercase tracking-wider text-center block mb-6 shadow-sm border border-yellow/20">
                                    Download Technical Handbook (THB)
                                </a>
                            @endif

                            <div class="text-xs font-semibold text-navy/80 leading-relaxed border-t border-gray-100 pt-4 whitespace-pre-line">
                                {{ $event->deskripsi }}
                            </div>
                        </div>
                    </div>

                    <!-- BLOK FORM REGISTRASI / NOTIFIKASI DAFTAR ULANG -->
                    <div class="w-full lg:w-7/12">
                        @if($sudahDaftar)
                            <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center shadow-sm">
                                @if($pendaftaranUser->status_pembayaran == 'Valid')
                                    <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="font-black text-navy text-lg uppercase mb-2">Pendaftaran Diterima</h3>
                                    <div class="inline-block px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-green-100 text-green-700 my-4">
                                        Status: VALID
                                    </div>
                                    <p class="text-xs font-bold text-navy/50 max-w-sm mx-auto mb-6">Berkas registrasi dan mutasi transaksi Anda telah diverifikasi oleh panitia pelaksana.</p>

                                    <a href="{{ route('event.open.history') }}" class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-xs uppercase tracking-wider transition-colors shadow-md block hover:bg-navy/90">
                                        Lihat Tiket QR Masuk
                                    </a>
                                @else
                                    <div class="w-16 h-16 bg-yellow/10 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-yellow/20">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="font-black text-navy text-lg uppercase mb-2">Berkas Sedang Diverifikasi</h3>
                                    <div class="inline-block px-5 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-yellow/20 text-yellow-700 border border-yellow/30 my-4">
                                        Status: MENUNGGU
                                    </div>
                                    <p class="text-xs font-bold text-navy/50 max-w-sm mx-auto">Mohon bersabar, bendahara FTI Lampung sedang memeriksa kesesuaian berkas serta mutasi nominal transaksi pendaftaran Anda.</p>
                                @endif
                            </div>
                        @else
                            <div class="bg-cream p-8 rounded-2xl border border-gray-200 shadow-sm">

                                @if($pendaftaranUser && $pendaftaranUser->status_pembayaran === 'Ditolak')
                                    <div class="mb-6 bg-red-50 border border-red-300 p-4 rounded-xl">
                                        <h4 class="text-xs font-black text-red-700 uppercase tracking-wide mb-1">Pendaftaran Sebelumnya Ditolak Panitia</h4>
                                        <p class="text-xs font-bold text-red-600/80">Alasan Penolakan: "{{ $pendaftaranUser->pesan_penolakan }}"</p>
                                        <p class="text-[10px] font-medium text-slate-500 mt-2">Anda diperbolehkan memperbaiki isian formulir atau mengunggah ulang bukti resi pembayaran yang valid menggunakan form di bawah ini.</p>
                                    </div>
                                @endif

                                <div class="mb-6 bg-white p-5 rounded-xl border-l-4 border-yellow shadow-sm">
                                    <h4 class="font-black text-navy uppercase text-xs tracking-wider mb-2">Tujuan Rekening Panitia FTI</h4>
                                    <p class="text-[11px] font-bold text-navy/70 uppercase"><strong class="text-navy">{{ $event->nama_bank }}</strong> | {{ $event->nomor_rekening }} | A.N {{ $event->atas_nama }}</p>
                                </div>

                                <h3 class="font-oswald text-2xl font-bold uppercase text-navy tracking-wide mb-6">Formulir Registrasi Mandiri</h3>

                                @if($errors->any())
                                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl">
                                        <p class="text-xs font-black text-red-700 uppercase mb-2">Gagal Mengirim Berkas, Periksa Kembali Isian Anda:</p>
                                        <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('event.open.register', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                        <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Biodata Pribadi</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama Lengkap Sesuai ID</label>
                                                <input type="text" name="nama_lengkap" value="{{ auth()->check() ? auth()->user()->name : '' }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy" required>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nomor Induk Kependudukan (KTP / KIA)</label>
                                                <input type="text" name="nomor_ktp" inputmode="numeric" pattern="[0-9]*" maxlength="16" minlength="16" placeholder="Masukkan 16 digit NIK" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                                            </div>

                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tanggal Lahir <span class="text-gray-400 font-medium">(Usia dihitung otomatis)</span></label>
                                                <input type="date" name="tanggal_lahir" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy" required>
                                                    <option value="Putra">Putra</option>
                                                    <option value="Putri">Putri</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Golongan Darah</label>
                                                <select name="golongan_darah" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-bold text-navy focus:outline-none focus:border-navy" required>
                                                    <option value="A">A Positif (A+)</option>
                                                    <option value="A">A Negatif (A-)</option>
                                                    <option value="B">B Positif (B+)</option>
                                                    <option value="B">B Negatif (B-)</option>
                                                    <option value="AB">AB Positif (AB+)</option>
                                                    <option value="AB">AB Negatif (AB-)</option>
                                                    <option value="O">O Positif (O+)</option>
                                                    <option value="O">O Negatif (O-)</option>
                                                    <option value="Tidak Tahu">Tidak Tahu</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Asal Daerah Domisili (Kabupaten / Kota & Provinsi)</label>
                                                <input type="text" name="asal_daerah" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" placeholder="Contoh: Kota Bandar Lampung, Lampung" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Alamat Lengkap Rumah</label>
                                                <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                        <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Informasi Atlet & Kontak</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Email Korespondensi</label>
                                                <input type="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none" required>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">No Kontak WhatsApp</label>
                                                <input type="text" name="nomor_telepon" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-semibold text-navy focus:outline-none" placeholder="Contoh: 08123456" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama BIB / Name Tag (Maks. 15 Huruf)</label>
                                                <input type="text" name="bib_name" maxlength="15" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-xs font-black text-navy uppercase focus:outline-none" placeholder="Contoh: ALDIAN" required>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Kategori Lomba (Ditetapkan Panitia)</label>
                                                <select name="kategori_lomba" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold text-navy focus:outline-none shadow-sm pointer-events-none" required>
                                                    <option value="{{ $event->kategori_lomba }}" selected>{{ $event->kategori_lomba }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                        <h4 class="text-[11px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2 mb-3">Pilihan Tarif Registrasi</h4>
                                        <div class="space-y-2 mb-4">
                                            @php $skemas = json_decode($event->skema_biaya, true) ?? []; @endphp
                                            @foreach($skemas as $s)
                                                <label class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-navy transition-colors">
                                                    <input type="radio" name="golongan_biaya" value="{{ $s['nama'] }}" class="w-4 h-4 text-navy focus:ring-navy cursor-pointer" required>
                                                    <span class="text-xs font-bold text-navy">{{ $s['nama'] }} <span class="text-gray-400 font-medium ml-1"> (Rp {{ number_format($s['biaya'], 0, ',', '.') }})</span></span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-2 mt-4">Unggah Resi Transaksi Pembayaran</label>
                                        <input type="file" name="bukti_transfer" accept="image/*" class="w-full text-xs text-navy file:mr-3 file:py-1.5 file:px-3 file:bg-navy file:text-yellow file:border-0 border border-gray-300 rounded p-0.5 bg-white cursor-pointer" required>
                                    </div>

                                    <button type="submit" class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-xs uppercase tracking-wider transition-colors shadow-md mt-4 hover:bg-navy/90">
                                        Kirim Berkas Registrasi
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            @endif

        </div>
    </section>

    <!-- POPUP MODAL UNTUK PREVIEW BUKTI TRANSFER ATLET -->
    <div id="proofModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeProofModal()"></div>
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden max-h-[85vh] animate-fadeIn">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-black text-navy uppercase text-xs tracking-wider">Lembar Bukti Resi Pembayaran Transaksi</h3>
                <button onclick="closeProofModal()" class="text-red-500 font-black text-xs uppercase hover:underline cursor-pointer">Tutup</button>
            </div>
            <div class="p-6 flex-1 overflow-y-auto bg-gray-100 flex items-center justify-center">
                <img id="proofImage" src="" alt="Bukti Transfer" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-md border border-gray-200">
            </div>
        </div>
    </div>

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

    <div id="scannerModal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-navy/90 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden max-h-[90vh] animate-fadeIn">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-navy text-white shrink-0">
                <h3 class="font-black uppercase text-sm tracking-wider">Pemindai Tiket Kehadiran</h3>
                <button onclick="closeScannerModal()" class="text-white/50 hover:text-white font-black text-sm uppercase cursor-pointer">Tutup</button>
            </div>

            <div class="p-4 flex flex-col items-center justify-start bg-gray-50 overflow-y-auto">
                <div id="reader" class="w-full max-w-sm overflow-hidden rounded-xl shadow-inner bg-black"></div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-4 text-center">Arahkan Kamera ke QR Code Peserta</p>

                <div id="scanResult" class="w-full mt-4 hidden rounded-xl p-4 border-2"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrcodeScanner;
        let requiresReload = false; // Tanda jika ada data baru masuk

        function openScannerModal() {
            document.getElementById('scannerModal').classList.remove('hidden');
            document.getElementById('scannerModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
            document.getElementById('scanResult').classList.add('hidden');
            requiresReload = false;

            // OPTIMASI KECEPATAN SCANNER: FPS ditingkatkan jadi 30, dibatasi hanya baca QR (Mencegah Lag)
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                {
                    fps: 30,
                    qrbox: {width: 250, height: 250},
                    formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
                    disableFlip: false
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }

        function closeScannerModal() {
            document.getElementById('scannerModal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
            if(html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }

            // Jika ada peserta yang berhasil absen, refresh halaman otomatis agar tabel terupdate
            if(requiresReload) {
                window.location.reload();
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            html5QrcodeScanner.pause();
            const resultBox = document.getElementById('scanResult');
            resultBox.classList.remove('hidden');
            resultBox.innerHTML = `<p class="text-center text-xs font-bold text-navy animate-pulse">Menghubungkan ke Database...</p>`;

            fetch("{{ route('event.open.checkin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                // REVISI: Mengirimkan parameter qr_token bersama dengan id event aktif dari server blade
                body: JSON.stringify({
                    qr_token: decodedText,
                    event_id: "{{ $event->id }}"
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
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
                    // TAMPILAN JIKA SALAH MEJA / SALAH EVENT (BOX AKAN BERWARNA MERAH)
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
                resultBox.innerHTML = `<p class="text-xs font-bold text-red-600 text-center">Terjadi kesalahan koneksi server.</p><button onclick="resumeScanner()" class="mt-4 bg-red-500 text-white px-4 py-2 text-[10px] font-black uppercase rounded-lg w-full">Coba Lagi</button>`;
            });
        }

        function resumeScanner() {
            document.getElementById('scanResult').classList.add('hidden');
            html5QrcodeScanner.resume();
        }

        function onScanFailure(error) {
            // Logika dibiarkan kosong
        }
    </script>
@endsection
