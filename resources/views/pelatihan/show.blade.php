@extends('layouts.main')

@section('title', 'Detail Pelatihan - ' . $pelatihan->judul)

@section('content')
    <section class="py-12 px-8 md:px-16 min-h-screen bg-[#0B1528]">
        <div class="max-w-7xl mx-auto">

            @if (auth()->check() && auth()->user()->email === 'admin@triatlon.test')
                <div class="mb-12 bg-[#F8F9FA] rounded-[2rem] p-8 shadow-xl">
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="font-black text-navy uppercase text-base">Validasi Kehadiran Pelatihan</h3>
                            <p class="text-xs text-navy/50 font-medium mt-1">Gunakan kamera untuk memindai tiket peserta
                                pelatihan.</p>
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <a href="{{ route('pelatihan.checkin.print', $pelatihan->id) }}" target="_blank"
                                class="text-center bg-gray-50 text-navy hover:bg-gray-100 border border-gray-300 px-4 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-sm transition-colors">
                                Cetak Absensi
                            </a>
                            <button onclick="openScannerModal()"
                                class="w-full md:w-auto bg-navy text-white hover:bg-yellow hover:text-navy border border-navy/20 px-6 py-3 font-black text-xs uppercase tracking-wider rounded-xl shadow-md transition-colors cursor-pointer">
                                [SCAN QR TIKET]
                            </button>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 overflow-hidden">
                        <h3 class="font-black text-navy uppercase text-sm mb-4 pb-2 border-b border-gray-100">Daftar
                            Pengajuan Registrasi Peserta</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse min-w-[900px]">
                                <thead>
                                    <tr class="bg-navy text-white uppercase font-bold tracking-wider">
                                        <th class="p-3 rounded-l-md w-[40%]">Data Peserta</th>
                                        <th class="p-3 w-[20%]">Golongan & Bukti</th>
                                        <th class="p-3 text-center w-[15%]">Waktu Hadir</th>
                                        <th class="p-3 text-center rounded-r-md w-[25%]">Aksi Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-gray-200 font-semibold text-navy">
                                    @forelse($allRegistrations as $reg)
                                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50/20 transition-colors">
                                            <td class="p-4">
                                                <p class="font-black uppercase text-sm text-navy mb-1">
                                                    {{ $reg->nama_lengkap }}</p>
                                                <div class="grid grid-cols-2 gap-2 text-[10px] mt-2">
                                                    <p><span class="text-gray-400 font-bold uppercase">Usia:</span>
                                                        {{ $reg->usia }} Thn</p>
                                                    <p><span class="text-gray-400 font-bold uppercase">Gender:</span>
                                                        {{ $reg->jenis_kelamin }}</p>
                                                    <p class="col-span-2"><span
                                                            class="text-gray-400 font-bold uppercase">Profesi:</span>
                                                        {{ $reg->pekerjaan }}</p>
                                                    <p class="col-span-2"><span
                                                            class="text-gray-400 font-bold uppercase">Baju:</span>
                                                        {{ $reg->ukuran_baju }}</p>
                                                </div>
                                            </td>
                                            <td class="p-4 align-top pt-5">
                                                <span
                                                    class="inline-block bg-white text-gray-600 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider mb-2 border border-gray-200">{{ $reg->golongan_biaya }}</span>
                                                <button type="button" data-url="{{ asset($reg->bukti_pembayaran) }}"
                                                    onclick="openProofModal(this.dataset.url)"
                                                    class="block mt-2 text-blue-600 hover:text-blue-800 underline font-bold text-[10px] cursor-pointer">
                                                    Cek Bukti TF
                                                </button>

                                                @if ($reg->surat_rekomendasi)
                                                    <a href="{{ asset('storage/' . $reg->surat_rekomendasi) }}"
                                                        target="_blank"
                                                        class="block mt-1 text-green-600 hover:text-green-800 underline font-bold text-[10px]">Cek
                                                        Surat Rekomendasi</a>
                                                @endif
                                            </td>
                                            <td class="p-4 text-center align-top pt-5">
                                                @if ($reg->waktu_checkin)
                                                    <span
                                                        class="text-green-600 font-black font-mono text-[10px]">{{ \Carbon\Carbon::parse($reg->waktu_checkin)->format('d/m/Y H:i:s') }}</span>
                                                @else
                                                    <span class="text-gray-400 font-bold text-[10px]">Belum Hadir</span>
                                                @endif
                                            </td>
                                            <td class="p-4 align-top pt-5">
                                                @if ($reg->status == 'Menunggu')
                                                    <form action="{{ route('pendaftaran.tolak', $reg->id) }}"
                                                        method="POST" class="flex flex-col items-end gap-2">
                                                        @csrf
                                                        <input type="text" name="pesan_penolakan"
                                                            placeholder="Alasan jika ditolak..."
                                                            class="bg-white border border-gray-300 rounded px-2 py-1.5 text-[10px] w-full font-semibold focus:outline-none focus:border-navy"
                                                            required>
                                                        <button type="submit"
                                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-1.5 rounded text-[9px] uppercase font-black transition-colors cursor-pointer">Tolak
                                                            Berkas</button>
                                                    </form>
                                                    <form action="{{ route('pendaftaran.terima', $reg->id) }}"
                                                        method="POST" class="w-full mt-1.5">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full bg-green-500 hover:bg-green-600 text-white py-1.5 rounded text-[9px] uppercase font-black transition-colors cursor-pointer">Terima
                                                            Berkas</button>
                                                    </form>
                                                @else
                                                    <div class="flex flex-col items-end justify-start gap-1">
                                                        <span
                                                            class="px-4 py-1.5 rounded text-[9px] font-black uppercase tracking-widest block w-full text-center border {{ $reg->status == 'Diterima' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                                            STATUS: {{ strtoupper($reg->status) }}
                                                        </span>
                                                        <form action="{{ route('pendaftaran.batal', $reg->id) }}"
                                                            method="POST" class="w-full text-right mt-1">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-[9px] text-navy/40 font-bold hover:text-navy hover:underline cursor-pointer w-full text-right">Batalkan
                                                                (Reset)
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="p-6 text-center text-navy/40 uppercase font-bold tracking-widest">
                                                Belum ada peserta.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-12">
                <div class="lg:w-1/3 text-white">
                    <a href="{{ url('/pelatihan') }}"
                        class="inline-flex items-center text-yellow text-xs font-bold uppercase tracking-wider hover:text-white transition-colors mb-6">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        KEMBALI KE DAFTAR
                    </a>
                    <h1 class="font-oswald text-4xl font-bold uppercase tracking-wide mb-6 leading-tight">
                        {{ $pelatihan->judul }}</h1>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="bg-white/5 border border-white/10 p-3 rounded-xl text-center">
                                <p class="text-[9px] font-black text-white/50 uppercase">Sisa Kuota</p>
                                <p class="text-lg font-black text-yellow">
                                    {{ max(0, $pelatihan->kuota_maksimal - $kuotaTerisi) }} /
                                    {{ $pelatihan->kuota_maksimal }}</p>
                            </div>
                            <div class="bg-white/5 border border-white/10 p-3 rounded-xl text-center">
                                <p class="text-[9px] font-black text-white/50 uppercase">Tgl. Pelaksanaan</p>
                                <p class="text-xs font-black text-white mt-1 uppercase">
                                    {{ \Carbon\Carbon::parse($pelatihan->tanggal_pelaksanaan)->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-yellow text-sm font-black uppercase mb-1">Deskripsi Kegiatan</h4>
                            <p class="text-sm font-semibold text-white/80 leading-relaxed text-justify">
                                {{ $pelatihan->deskripsi }}</p>
                        </div>

                        <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                            <h4 class="text-yellow text-sm font-black uppercase mb-3">Biaya Pendaftaran</h4>
                            <ul class="space-y-3">
                                @if (is_array($pelatihan->biaya) && count($pelatihan->biaya) > 0)
                                    @foreach ($pelatihan->biaya as $biaya)
                                        <li class="flex justify-between items-center border-b border-white/10 pb-2">
                                            <span class="text-sm font-semibold">{{ $biaya['nama'] }}</span>
                                            <span class="font-black">Rp
                                                {{ number_format($biaya['nominal'], 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-sm font-semibold text-white/60">Gratis / Tidak ada pungutan biaya.</li>
                                @endif
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-yellow text-sm font-black uppercase mb-1">Rekening Pembayaran</h4>
                            <p class="text-sm font-semibold text-white/80 leading-relaxed">{{ $pelatihan->rekening }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:w-2/3">
                    <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-xl">

                        @if (session('success'))
                            <div
                                class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        @php
                            $userPendaftaran = auth()->check()
                                ? \App\Models\Pendaftaran::where('user_id', auth()->id())
                                    ->where('pelatihan_id', $pelatihan->id)
                                    ->first()
                                : null;
                            $isLocked =
                                $pelatihan->status == 'Tutup' ||
                                $pelatihan->status == 'Selesai' ||
                                $kuotaTerisi >= $pelatihan->kuota_maksimal;
                            $isRejected = $userPendaftaran && $userPendaftaran->status === 'Ditolak';
                            $isAdmin = auth()->check() && auth()->user()->email === 'admin@triatlon.test';
                        @endphp

                        @if ($isAdmin)
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-navy/5 text-navy rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-black text-navy uppercase mb-2">Mode Admin</h3>
                                <p class="text-sm font-semibold text-navy/60 max-w-md mx-auto">Formulir pendaftaran peserta
                                    disembunyikan untuk akun admin. Gunakan panel validasi registrasi di bagian atas halaman
                                    ini untuk menerima, menolak, atau memvalidasi kehadiran peserta.</p>
                            </div>
                        @elseif($userPendaftaran && $userPendaftaran->status === 'Menunggu')
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-yellow/20 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-navy uppercase mb-2">Pendaftaran Menunggu Validasi</h3>
                                <p class="text-sm font-semibold text-navy/70 max-w-md mx-auto">Sistem sedang memeriksa
                                    bukti pembayaran Anda. Hasil verifikasi akan diperbarui pada halaman ini secara berkala.
                                </p>
                            </div>
                        @elseif($userPendaftaran && $userPendaftaran->status === 'Diterima')
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-green-600 uppercase mb-2">Selamat, Anda Terdaftar!</h3>
                                <p class="text-sm font-semibold text-navy/70 max-w-md mx-auto mb-8">Pendaftaran Anda telah
                                    disetujui. Silakan cetak bukti tiket QR Anda untuk absen kehadiran di lokasi.</p>

                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    <a href="{{ route('pendaftaran.tiket', $pelatihan->id) }}"
                                        class="inline-flex justify-center items-center bg-navy text-yellow px-8 py-4 font-black text-sm uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                                        CETAK TIKET QR
                                    </a>
                                    @if ($pelatihan->link_wa_grup)
                                        <a href="{{ $pelatihan->link_wa_grup }}" target="_blank"
                                            class="inline-flex justify-center items-center bg-green-600 text-white px-8 py-4 font-black text-sm uppercase rounded-xl hover:bg-green-700 transition-colors shadow-lg">
                                            MASUK GRUP WA
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($isLocked && !$isRejected)
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-red-600 uppercase mb-2">Pendaftaran Ditutup</h3>
                                <p class="text-sm font-semibold text-red-500/80 max-w-md mx-auto">Mohon maaf, pendaftaran
                                    untuk pelatihan ini sudah tidak tersedia karena telah melewati batas waktu atau kuota
                                    penuh.</p>
                            </div>
                        @else
                            @if ($isRejected)
                                <div class="mb-6 bg-red-100 border-l-4 border-red-500 p-4 rounded-xl text-sm shadow-sm">
                                    <h4 class="font-black text-red-700 uppercase mb-1">Pendaftaran Ditolak</h4>
                                    <p class="font-bold text-red-600">Alasan: "{{ $userPendaftaran->alasan_ditolak }}"</p>
                                    <p class="text-xs text-red-500 mt-2 font-medium">Silakan perbaiki data/bukti transfer
                                        Anda dan kirim ulang formulir di bawah ini.</p>
                                </div>
                                <form action="{{ route('pendaftaran.resubmit', $userPendaftaran->id) }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-6">
                                @else
                                    <h2
                                        class="font-black text-2xl text-navy uppercase mb-8 border-b-2 border-navy/10 pb-4">
                                        FORMULIR PENDAFTARAN PESERTA</h2>
                                    <form action="{{ route('pendaftaran.store', $pelatihan->id) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-6">
                            @endif
                            @csrf

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Pilih Golongan Biaya <span
                                        class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if (is_array($pelatihan->biaya) && count($pelatihan->biaya) > 0)
                                        @foreach ($pelatihan->biaya as $index => $biaya)
                                            <label
                                                class="flex items-center space-x-3 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                                <input type="radio" name="golongan_biaya" value="{{ $biaya['nama'] }}"
                                                    class="w-4 h-4 text-navy focus:ring-yellow"
                                                    {{ (isset($userPendaftaran) && $userPendaftaran->golongan_biaya == $biaya['nama']) || $index == 0 ? 'checked' : '' }}
                                                    required>
                                                <span>{{ $biaya['nama'] }} (Rp
                                                    {{ number_format($biaya['nominal'], 0, ',', '.') }})</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap"
                                    value="{{ $userPendaftaran->nama_lengkap ?? '' }}"
                                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                                    placeholder="Masukkan nama lengkap beserta gelar" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Jenis Kelamin <span
                                            class="text-red-500">*</span></label>
                                    <div class="flex space-x-4">
                                        <label
                                            class="flex-1 flex items-center justify-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                            <input type="radio" name="jenis_kelamin" value="LAKI-LAKI"
                                                {{ isset($userPendaftaran) && $userPendaftaran->jenis_kelamin == 'LAKI-LAKI' ? 'checked' : '' }}
                                                class="w-4 h-4 text-navy focus:ring-yellow" required>
                                            <span>LAKI-LAKI</span>
                                        </label>
                                        <label
                                            class="flex-1 flex items-center justify-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                            <input type="radio" name="jenis_kelamin" value="PEREMPUAN"
                                                {{ isset($userPendaftaran) && $userPendaftaran->jenis_kelamin == 'PEREMPUAN' ? 'checked' : '' }}
                                                class="w-4 h-4 text-navy focus:ring-yellow">
                                            <span>PEREMPUAN</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Usia <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="usia" value="{{ $userPendaftaran->usia ?? '' }}"
                                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                                        placeholder="Misal: 25" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Pernah Menjadi Pelatih Apa? <span
                                        class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    @php $pengalaman = ['ATLETIK', 'SEPEDA', 'RENANG', 'BELUM PERNAH']; @endphp
                                    @foreach ($pengalaman as $p)
                                        <label
                                            class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                            <input type="radio" name="pengalaman_melatih" value="{{ $p }}"
                                                {{ isset($userPendaftaran) && $userPendaftaran->pengalaman_melatih == $p ? 'checked' : '' }}
                                                class="w-4 h-4 text-navy focus:ring-yellow" required>
                                            <span class="text-xs">{{ $p }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Pekerjaan / Profesi <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="pekerjaan"
                                        value="{{ $userPendaftaran->pekerjaan ?? '' }}"
                                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Asal Daerah / Provinsi <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="asal_daerah"
                                        value="{{ $userPendaftaran->asal_daerah ?? '' }}"
                                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Ukuran Baju <span
                                        class="text-red-500">*</span></label>
                                <select name="ukuran_baju"
                                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm appearance-none cursor-pointer"
                                    required>
                                    <option value="" disabled {{ !isset($userPendaftaran) ? 'selected' : '' }}>Pilih
                                        Ukuran</option>
                                    @foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                        <option value="{{ $size }}"
                                            {{ isset($userPendaftaran) && $userPendaftaran->ukuran_baju == $size ? 'selected' : '' }}>
                                            {{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-navy/10 mt-6">
                                <div
                                    class="border-2 border-dashed border-gray-400 rounded-2xl p-6 bg-white/40 text-center hover:bg-white hover:border-yellow transition-all group">
                                    <label class="block text-sm font-bold text-navy mb-1">Bukti Pembayaran <span
                                            class="text-red-500">*</span></label>
                                    @if ($isRejected)
                                        <p class="text-[9px] text-red-500 font-bold mb-2">Unggah bukti transfer baru jika
                                            ditolak karena mutasi tidak valid.</p>
                                    @endif
                                    <input type="file" name="bukti_pembayaran" accept="image/*"
                                        class="block w-full text-sm text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer mx-auto"
                                        {{ !$isRejected ? 'required' : '' }}>
                                </div>

                                <div
                                    class="border-2 border-dashed border-gray-400 rounded-2xl p-6 bg-white/40 text-center hover:bg-white hover:border-yellow transition-all group">
                                    <label class="block text-sm font-bold text-navy mb-1">Surat Rekomendasi MGMP</label>
                                    <p class="text-[10px] font-bold text-navy/60 mb-4">Opsional (Format: PDF, Max: 2MB)</p>
                                    <input type="file" name="surat_rekomendasi" accept=".pdf"
                                        class="block w-full text-sm text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-gray-300 file:text-navy hover:file:bg-gray-400 cursor-pointer mx-auto">
                                </div>
                            </div>

                            <div class="pt-8">
                                @auth
                                    <button type="submit"
                                        class="w-full bg-yellow text-navy py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-xl transform hover:-translate-y-1">
                                        {{ $isRejected ? 'KIRIM PERBAIKAN BERKAS' : 'KIRIM PENDAFTARAN' }}
                                    </button>
                                @else
                                    <a href="{{ url('/login') }}"
                                        class="block w-full text-center bg-gray-400 text-white py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-xl">
                                        REGISTER/LOGIN DULU
                                    </a>
                                @endauth
                            </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </section>

    <div id="proofModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeProofModal()"></div>
        <div
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 flex flex-col overflow-hidden max-h-[85vh] animate-fadeIn">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 shrink-0">
                <h3 class="font-black text-navy uppercase text-xs tracking-wider">Bukti Resi Pembayaran</h3>
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

                html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                    fps: 30,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                    disableFlip: false
                }, false);
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

                fetch("{{ route('pelatihan.checkin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            qr_token: decodedText,
                            pelatihan_id: "{{ $pelatihan->id }}"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            requiresReload = true;
                            resultBox.className = "w-full mt-4 rounded-xl p-4 border-2 bg-green-50 border-green-400";
                            resultBox.innerHTML =
                                `<div class="text-center"><h4 class="font-black text-green-700 uppercase text-lg">CHECK-IN BERHASIL</h4><p class="font-black text-navy uppercase text-sm mt-2">${data.data.nama}</p><button onclick="closeScannerModal()" class="mt-4 bg-navy text-yellow px-4 py-3 w-full text-xs font-black uppercase rounded-lg shadow-md cursor-pointer hover:bg-yellow hover:text-navy transition-colors">Tutup & Perbarui Tabel</button></div>`;
                        } else {
                            resultBox.className = "w-full mt-4 rounded-xl p-4 border-2 bg-red-50 border-red-400";
                            resultBox.innerHTML =
                                `<div class="text-center"><h4 class="font-black text-red-700 uppercase text-sm mb-2">GAGAL VALIDASI ABSENSI</h4><p class="text-xs font-bold text-red-600 leading-relaxed px-2 mb-4">${data.message}</p><button onclick="resumeScanner()" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 text-[10px] font-black uppercase rounded-lg cursor-pointer shadow-sm">Scan Tiket Lain</button></div>`;
                        }
                    }).catch(error => {
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
