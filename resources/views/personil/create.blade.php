@extends('layouts.main')

@section('title', 'Tambah Data SDM Olahraga - FTI LAMPUNG')

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ url('/personil') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Direktori SDM
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-2xl shadow-xl border border-gray-200">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">Registrasi SDM Baru</h2>
                <p class="text-sm text-navy/70 mb-8 pb-6 border-b border-navy/10">Silakan tentukan kategori terlebih dahulu untuk membuka kolom data yang spesifik.</p>

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-200 p-4 rounded-xl">
                        <div class="flex items-center gap-2 mb-2 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="font-black text-sm uppercase tracking-wider">Gagal Menyimpan Data!</h3>
                        </div>
                        <ul class="list-disc list-inside text-xs font-bold text-red-500/80 ml-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('personil.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Kategori SDM <span class="text-red-500">*</span></label>
                            <select name="kategori" id="kategori-sdm" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy transition-colors shadow-sm" required>
                                <option value="" disabled selected>-- Pilih Peran --</option>
                                <option value="Atlet" {{ old('kategori') == 'Atlet' ? 'selected' : '' }}>Atlet</option>
                                <option value="Pelatih" {{ old('kategori') == 'Pelatih' ? 'selected' : '' }}>Pelatih</option>
                                <option value="Wasit" {{ old('kategori') == 'Wasit' ? 'selected' : '' }}>Wasit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Nama Lengkap Anggota <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy transition-colors shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Asal Wilayah Kabupaten / Kota <span class="text-red-500">*</span></label>
                            <select name="asal_daerah" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy transition-colors shadow-sm" required>
                                <option value="" disabled selected>-- Pilih Asal Daerah --</option>
                                @php
                                    $daerah = ['Bandar Lampung', 'Metro', 'Pesawaran', 'Pringsewu', 'Tanggamus', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Utara', 'Lampung Barat', 'Lampung Timur', 'Way Kanan', 'Tulang Bawang', 'Tulang Bawang Barat', 'Mesuji', 'Pesisir Barat'];
                                @endphp
                                @foreach($daerah as $d)
                                    <option value="{{ $d }}" {{ old('asal_daerah') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">No WhatsApp Aktif (Gunakan Kode Negara / 08...)</label>
                            <input type="text" name="kontak" value="{{ old('kontak') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy transition-colors shadow-sm" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div id="blok-atlet" class="hidden bg-white p-6 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Tempat, Tanggal Lahir</label>
                            <input type="text" name="ttl" id="input-ttl" value="{{ old('ttl') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" placeholder="Tempat, tanggal bulan tahun">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Usia / Umur (Tahun)</label>
                            <input type="number" name="umur" id="input-umur" value="{{ old('umur') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" placeholder="Contoh: 22">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Jenis Identitas</label>
                            <select name="jenis_identitas" id="input-jenis-id" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy">
                                <option value="NIK" {{ old('jenis_identitas') == 'NIK' ? 'selected' : '' }}>NIK (KTP)</option>
                                <option value="NIM" {{ old('jenis_identitas') == 'NIM' ? 'selected' : '' }}>NIM (Mahasiswa)</option>
                                <option value="NISN" {{ old('jenis_identitas') == 'NISN' ? 'selected' : '' }}>NISN (Siswa)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Nomor Kode Identitas</label>
                            <input type="text" name="nomor_identitas" id="input-nomor-id" value="{{ old('nomor_identitas') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy">
                        </div>
                    </div>

                    <div id="blok-lisensi" class="hidden bg-white p-6 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Kualifikasi Tingkat Lisensi</label>
                            <input type="text" name="tingkat_lisensi" id="input-lisensi" value="{{ old('tingkat_lisensi') }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-xs font-semibold text-navy focus:outline-none focus:border-navy" placeholder="Contoh: Lisensi B Nasional">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Unggah Sertifikat Lisensi (PDF)</label>
                            <input type="file" name="sertifikat_lisensi" id="input-file-sk" accept=".pdf" class="w-full text-xs text-navy file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-navy file:text-yellow cursor-pointer">
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Pas Foto Profil (JPG/PNG)</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-xs text-navy file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-100 file:text-navy border border-gray-300 rounded-lg p-1 bg-gray-50 cursor-pointer">
                    </div>

                    <div class="pt-6 border-t border-navy/10 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-10 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                            Daftarkan Data SDM
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script>
        const selectKategori = document.getElementById('kategori-sdm');
        const blokAtlet = document.getElementById('blok-atlet');
        const blokLisensi = document.getElementById('blok-lisensi');

        function updateFormState() {
            if (selectKategori.value === 'Atlet') {
                blokAtlet.classList.remove('hidden');
                blokLisensi.classList.add('hidden');

                document.getElementById('input-ttl').removeAttribute('disabled');
                document.getElementById('input-umur').removeAttribute('disabled');
                document.getElementById('input-jenis-id').removeAttribute('disabled');
                document.getElementById('input-nomor-id').removeAttribute('disabled');

                document.getElementById('input-lisensi').setAttribute('disabled', 'true');
                document.getElementById('input-file-sk').setAttribute('disabled', 'true');
            } else if (selectKategori.value === 'Pelatih' || selectKategori.value === 'Wasit') {
                blokAtlet.classList.add('hidden');
                blokLisensi.classList.remove('hidden');

                document.getElementById('input-ttl').setAttribute('disabled', 'true');
                document.getElementById('input-umur').setAttribute('disabled', 'true');
                document.getElementById('input-jenis-id').setAttribute('disabled', 'true');
                document.getElementById('input-nomor-id').setAttribute('disabled', 'true');

                document.getElementById('input-lisensi').removeAttribute('disabled');
                document.getElementById('input-file-sk').removeAttribute('disabled');
            } else {
                blokAtlet.classList.add('hidden');
                blokLisensi.classList.add('hidden');

                document.getElementById('input-ttl').setAttribute('disabled', 'true');
                document.getElementById('input-umur').setAttribute('disabled', 'true');
                document.getElementById('input-jenis-id').setAttribute('disabled', 'true');
                document.getElementById('input-nomor-id').setAttribute('disabled', 'true');
                document.getElementById('input-lisensi').setAttribute('disabled', 'true');
                document.getElementById('input-file-sk').setAttribute('disabled', 'true');
            }
        }

        document.addEventListener("DOMContentLoaded", updateFormState);
        selectKategori.addEventListener('change', updateFormState);
    </script>
@endsection
