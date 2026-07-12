@extends('layouts.main')
@section('title', 'Tambah Venue - FTI LAMPUNG')

@section('content')
@php
    $kabupatenLampung = ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pesisir Barat', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'];
@endphp

<section class="bg-navy py-12 px-6 md:px-16 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('venue.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            KEMBALI KE DIREKTORI
        </a>

        <div class="bg-cream p-8 md:p-10 shadow-2xl border-t-4 border-yellow">
            <h2 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy tracking-wide mb-8 border-b border-navy/10 pb-4">Tambah Data Venue Baru</h2>

            <form action="{{ route('venue.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Nama Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Penggolongan Venue <span class="text-red-500">*</span></label>
                        <select name="tingkat" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                            <option value="" disabled selected>Pilih Tingkatan...</option>
                            <option value="Daerah / Lokal">Daerah / Lokal</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Daerah / Kabupaten <span class="text-red-500">*</span></label>
                        <select name="daerah" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                            <option value="" disabled selected>Pilih Kabupaten/Kota...</option>
                            @foreach($kabupatenLampung as $kab)
                                <option value="{{ strtoupper($kab) }}">{{ strtoupper($kab) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Link Google Maps</label>
                        <input type="url" name="link_maps" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-navy uppercase mb-1.5">Deskripsi Venue</label>
                    <textarea name="deskripsi" rows="4" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" placeholder="Ceritakan keunggulan dan kondisi venue ini..."></textarea>
                </div>

                <div class="bg-white p-6 border border-gray-300 rounded">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <label class="block text-xs font-bold text-navy uppercase">Fasilitas Tersedia</label>
                        <button type="button" id="btn-tambah-fasilitas" class="text-[10px] bg-navy text-yellow px-3 py-1.5 font-black uppercase hover:bg-yellow hover:text-navy transition-colors">+ Tambah Fasilitas</button>
                    </div>
                    <div id="container-fasilitas" class="space-y-3">
                        <div class="flex items-center gap-2 baris-fasilitas">
                            <input type="text" name="fasilitas[]" class="flex-1 bg-gray-50 border border-gray-300 rounded px-4 py-2 text-sm font-semibold focus:border-navy" placeholder="Contoh: Toilet Bersih / Area Parkir Luas">
                            <button type="button" class="btn-hapus-fasilitas text-gray-400 hover:text-red-600 p-2 transition-colors">
                                <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-navy/5 p-6 border border-navy/10 rounded">
                    <h3 class="font-black text-navy uppercase text-sm border-b border-gray-300 pb-2 mb-2">Informasi Rute Lintasan</h3>
                    <p class="text-xs font-semibold text-navy/60 mb-6">Triatlon terdiri dari 3 tahapan. Deskripsikan secara singkat di mana atlet akan melakukan setiap rute di dalam area venue ini.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Renang (Swim)</label>
                            <input type="text" name="rute_renang" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy" placeholder="Contoh: Danau buatan sedalam 2 meter">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Sepeda (Bike)</label>
                            <input type="text" name="rute_sepeda" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy" placeholder="Contoh: Jalan aspal di luar kawasan venue">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Lari (Run)</label>
                            <input type="text" name="rute_lari" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy" placeholder="Contoh: Jogging track memutari area taman">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Area Transisi (T1 & T2)</label>
                            <input type="text" name="area_transisi" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy" placeholder="Contoh: Lapangan rumput tengah">
                        </div>
                    </div>
                </div>

                <div class="border-2 border-dashed border-gray-400 rounded p-8 bg-white text-center hover:border-navy transition-colors">
                    <label class="block text-sm font-black text-navy uppercase mb-2">Unggah Galeri Foto Venue <span class="text-red-500">*</span></label>
                    <p class="text-[10px] font-bold text-navy/60 mb-4">Pilih beberapa foto sekaligus (Max 3MB per foto). Foto pertama akan menjadi sampul utama.</p>
                    <input type="file" name="fotos[]" accept="image/*" multiple class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow hover:file:bg-yellow hover:file:text-navy cursor-pointer mx-auto" required>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-300">
                    <a href="{{ route('venue.index') }}" class="px-6 py-3 font-bold text-sm text-navy uppercase hover:text-red-600 mr-2 transition-colors">Batal</a>
                    <button type="submit" class="bg-navy text-yellow hover:bg-yellow hover:text-navy transition-colors px-8 py-3 rounded font-black text-sm uppercase shadow-md">SIMPAN VENUE</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.getElementById('btn-tambah-fasilitas').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 baris-fasilitas mt-2';
        row.innerHTML = `<input type="text" name="fasilitas[]" class="flex-1 bg-gray-50 border border-gray-300 rounded px-4 py-2 text-sm font-semibold focus:border-navy"><button type="button" class="btn-hapus-fasilitas text-gray-400 hover:text-red-600 p-2 transition-colors"><svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>`;
        document.getElementById('container-fasilitas').appendChild(row);
    });

    document.getElementById('container-fasilitas').addEventListener('click', function(e) {
        if(e.target.closest('.btn-hapus-fasilitas')) {
            e.target.closest('.baris-fasilitas').remove();
        }
    });
</script>
@endsection
