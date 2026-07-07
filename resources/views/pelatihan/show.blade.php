@extends('layouts.main')

@section('title', 'Detail Pelatihan - ' . $pelatihan->judul)

@section('content')
    <section class="py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-12">

            <div class="lg:w-1/3 text-white">
                <a href="{{ url('/pelatihan') }}" class="inline-flex items-center text-yellow text-xs font-bold uppercase tracking-wider hover:text-white transition-colors mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    KEMBALI KE DAFTAR
                </a>

                <h1 class="font-oswald text-4xl font-bold uppercase tracking-wide mb-6 leading-tight">{{ $pelatihan->judul }}</h1>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-yellow text-sm font-black uppercase mb-1">Deskripsi Kegiatan</h4>
                        <p class="text-sm font-semibold text-white/80 leading-relaxed text-justify">{{ $pelatihan->deskripsi }}</p>
                    </div>

                    <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                        <h4 class="text-yellow text-sm font-black uppercase mb-3">Biaya Pendaftaran</h4>
                        <ul class="space-y-3">
                            @if(is_array($pelatihan->biaya) && count($pelatihan->biaya) > 0)
                                @foreach($pelatihan->biaya as $biaya)
                                    <li class="flex justify-between items-center border-b border-white/10 pb-2">
                                        <span class="text-sm font-semibold">{{ $biaya['nama'] }}</span>
                                        <span class="font-black">Rp {{ number_format($biaya['nominal'], 0, ',', '.') }}</span>
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

                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @php
                        $userPendaftaran = auth()->check() ? \App\Models\Pendaftaran::where('user_id', auth()->id())->where('pelatihan_id', $pelatihan->id)->first() : null;
                    @endphp

                    @if($userPendaftaran && $userPendaftaran->status === 'Menunggu')
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-yellow/20 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-navy uppercase mb-2">Pendaftaran Menunggu Validasi</h3>
                            <p class="text-sm font-semibold text-navy/70 max-w-md mx-auto">Sistem sedang memeriksa bukti pembayaran Anda. Hasil verifikasi akan diperbarui pada halaman ini secara berkala.</p>
                        </div>
                    @elseif($userPendaftaran && $userPendaftaran->status === 'Diterima')
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-green-600 uppercase mb-2">Selamat, Anda Terdaftar!</h3>
                            <p class="text-sm font-semibold text-navy/70 max-w-md mx-auto mb-8">Pendaftaran Anda telah disetujui oleh admin. Silakan unduh atau cetak kartu bukti pendaftaran resmi Anda di bawah ini untuk dibawa saat verifikasi fisik.</p>

                            <a href="{{ route('pendaftaran.tiket', $pelatihan->id) }}" class="inline-flex items-center bg-navy text-yellow px-8 py-4 font-black text-sm uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-lg transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                DOWNLOAD BUKTI PENDAFTARAN
                            </a>
                        </div>
                    @else
                        @if($userPendaftaran && $userPendaftaran->status === 'Ditolak')
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm font-bold">
                                Pendaftaran Sebelumnya Ditolak. Alasan: "{{ $userPendaftaran->alasan_ditolak }}". Silakan perbaiki data dan kirim ulang formulir di bawah ini.
                            </div>
                        @endif

                        <h2 class="font-black text-2xl text-navy uppercase mb-8 border-b-2 border-navy/10 pb-4">FORMULIR PENDAFTARAN PESERTA</h2>

                        <form action="{{ route('pendaftaran.store', $pelatihan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Pilih Golongan Biaya <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if(is_array($pelatihan->biaya) && count($pelatihan->biaya) > 0)
                                        @foreach($pelatihan->biaya as $index => $biaya)
                                            <label class="flex items-center space-x-3 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                                <input type="radio" name="golongan_biaya" value="{{ $biaya['nama'] }}" class="w-4 h-4 text-navy focus:ring-yellow" {{ $index == 0 ? 'required' : '' }}>
                                                <span>{{ $biaya['nama'] }} (Rp {{ number_format($biaya['nominal'], 0, ',', '.') }})</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Masukkan nama lengkap beserta gelar" required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <div class="flex space-x-4">
                                        <label class="flex-1 flex items-center justify-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                            <input type="radio" name="jenis_kelamin" value="LAKI-LAKI" class="w-4 h-4 text-navy focus:ring-yellow" static required>
                                            <span>LAKI-LAKI</span>
                                        </label>
                                        <label class="flex-1 flex items-center justify-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                            <input type="radio" name="jenis_kelamin" value="PEREMPUAN" class="w-4 h-4 text-navy focus:ring-yellow">
                                            <span>PEREMPUAN</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Usia <span class="text-red-500">*</span></label>
                                    <input type="number" name="usia" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Misal: 25" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Pernah Menjadi Pelatih Apa? <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                        <input type="radio" name="pengalaman_melatih" value="ATLETIK" class="w-4 h-4 text-navy focus:ring-yellow" required>
                                        <span>ATLETIK</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                        <input type="radio" name="pengalaman_melatih" value="SEPEDA" class="w-4 h-4 text-navy focus:ring-yellow">
                                        <span>SEPEDA</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                        <input type="radio" name="pengalaman_melatih" value="RENANG" class="w-4 h-4 text-navy focus:ring-yellow">
                                        <span>RENANG</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer bg-white border border-gray-300 rounded-xl px-4 py-3 hover:border-yellow hover:bg-yellow/10 transition-all shadow-sm">
                                        <input type="radio" name="pengalaman_melatih" value="BELUM PERNAH" class="w-4 h-4 text-navy focus:ring-yellow">
                                        <span class="text-xs">BELUM PERNAH</span>
                                    </label>
                                </div>
                                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-4 py-1 hover:border-yellow transition-all shadow-sm focus-within:ring-2 focus-within:ring-yellow focus-within:border-yellow">
                                    <label class="flex items-center space-x-2 text-sm font-semibold text-navy cursor-pointer whitespace-nowrap border-r border-gray-200 pr-4 mr-2">
                                        <input type="radio" name="pengalaman_melatih" value="Lainnya" class="w-4 h-4 text-navy focus:ring-yellow">
                                        <span>Yang lain:</span>
                                    </label>
                                    <input type="text" name="pengalaman_lainnya" class="w-full bg-transparent border-none px-2 py-2 text-sm font-semibold text-navy focus:outline-none focus:ring-0" placeholder="Sebutkan...">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Pekerjaan / Profesi <span class="text-red-500">*</span></label>
                                    <input type="text" name="pekerjaan" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: Guru Olahraga" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-navy mb-2">Asal Daerah / Provinsi <span class="text-red-500">*</span></label>
                                    <input type="text" name="asal_daerah" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: Bandar Lampung" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-navy mb-2">Ukuran Baju <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="ukuran_baju" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm appearance-none cursor-pointer" required>
                                        <option value="" disabled selected>Pilih Ukuran</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-navy/10 mt-6">
                                <div class="border-2 border-dashed border-gray-400 rounded-2xl p-6 bg-white/40 text-center hover:bg-white hover:border-yellow transition-all group">
                                    <label class="block text-sm font-bold text-navy mb-1">Bukti Pembayaran <span class="text-red-500">*</span></label>
                                    <p class="text-[10px] font-bold text-navy/60 mb-4">Format: JPG, PNG (Max: 2MB)</p>
                                    <input type="file" name="bukti_pembayaran" accept="image/*" class="block w-full text-sm text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer mx-auto" required>
                                </div>

                                <div class="border-2 border-dashed border-gray-400 rounded-2xl p-6 bg-white/40 text-center hover:bg-white hover:border-yellow transition-all group">
                                    <label class="block text-sm font-bold text-navy mb-1">Surat Rekomendasi MGMP</label>
                                    <p class="text-[10px] font-bold text-navy/60 mb-4">Opsional (Format: PDF, Max: 2MB)</p>
                                    <input type="file" name="surat_rekomendasi" accept=".pdf" class="block w-full text-sm text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-gray-300 file:text-navy hover:file:bg-gray-400 cursor-pointer mx-auto">
                                </div>
                            </div>

                            <div class="pt-8">
                                @auth
                                    <button type="submit" class="w-full bg-yellow text-navy py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-xl transform hover:-translate-y-1">
                                        KIRIM PENDAFTARAN
                                    </button>
                                @else
                                    <a href="{{ url('/login') }}" class="block w-full text-center bg-gray-400 text-white py-4 rounded-xl font-black text-lg uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-xl">
                                        REGISTER/LOGIN DULU
                                    </a>
                                @endauth
                            </div>
                        </form>
                    @endif

                </div>
            </div>

        </div>
    </section>
@endsection
