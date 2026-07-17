@extends('layouts.main')
@section('title', 'Edit Venue - FTI LAMPUNG')

@section('content')
@php
    $kabupatenLampung = ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pesisir Barat', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'];
@endphp

<section class="bg-navy py-12 px-6 md:px-16 min-h-screen relative">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('venue.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            KEMBALI KE DIREKTORI
        </a>

        <div class="bg-cream p-8 md:p-10 shadow-2xl border-t-4 border-yellow relative z-10">
            <h2 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy tracking-wide mb-8 border-b border-navy/10 pb-4">Edit Data Venue</h2>

            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 p-4 text-sm text-red-700 font-bold">
                    Terdapat kesalahan input, mohon periksa kembali form Anda.
                </div>
            @endif

            <!-- MANAJEMEN GALERI LAMA -->
            <div class="mb-10 bg-white p-6 border border-gray-300 rounded">
                <label class="block text-xs font-black text-navy uppercase mb-4 border-b border-gray-100 pb-2">Foto Tersimpan di Galeri</label>
                @if($venue->photos->count() > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                        @foreach($venue->photos as $foto)
                            <div class="relative aspect-square rounded overflow-hidden border border-gray-200 group">
                                <img src="{{ asset($foto->path_foto) }}" class="w-full h-full object-cover">
                                <form action="{{ route('venue.foto.destroy', $foto->id) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white w-7 h-7 rounded-sm flex items-center justify-center cursor-pointer hover:bg-red-700" title="Hapus Foto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[9px] text-gray-500 font-bold mt-3 uppercase tracking-widest">Arahkan kursor ke foto untuk memunculkan tombol hapus.</p>
                @else
                    <p class="text-sm font-bold text-gray-400 italic">Belum ada foto yang diunggah.</p>
                @endif
            </div>

            <!-- FORM UTAMA -->
            <form action="{{ route('venue.update', $venue->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Nama Venue <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $venue->nama) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Penggolongan Venue <span class="text-red-500">*</span></label>
                        <select name="tingkat" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                            <option value="Daerah / Lokal" {{ old('tingkat', $venue->tingkat) == 'Daerah / Lokal' ? 'selected' : '' }}>Daerah / Lokal</option>
                            <option value="Nasional" {{ old('tingkat', $venue->tingkat) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="Internasional" {{ old('tingkat', $venue->tingkat) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>{{ old('alamat', $venue->alamat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Daerah / Kabupaten <span class="text-red-500">*</span></label>
                        <select name="daerah" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0" required>
                            @foreach($kabupatenLampung as $kab)
                                <option value="{{ strtoupper($kab) }}" {{ old('daerah', $venue->daerah) == strtoupper($kab) ? 'selected' : '' }}>{{ strtoupper($kab) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-navy uppercase mb-1.5">Link Google Maps</label>
                        <input type="url" name="link_maps" value="{{ old('link_maps', $venue->link_maps) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-navy uppercase mb-1.5">Deskripsi Venue</label>
                    <textarea name="deskripsi" rows="4" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-sm font-semibold text-navy focus:border-navy focus:ring-0">{{ old('deskripsi', $venue->deskripsi) }}</textarea>
                </div>

                <div class="bg-white p-6 border border-gray-300 rounded">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <label class="block text-xs font-bold text-navy uppercase">Fasilitas Tersedia</label>
                        <button type="button" id="btn-tambah-fasilitas" class="text-[10px] bg-navy text-yellow px-3 py-1.5 font-black uppercase hover:bg-yellow hover:text-navy transition-colors">+ Tambah Fasilitas</button>
                    </div>
                    <div id="container-fasilitas" class="space-y-3">
                        @php $fasilitasArray = old('fasilitas', is_array($venue->fasilitas) ? $venue->fasilitas : json_decode($venue->fasilitas, true)); @endphp

                        @if(!empty($fasilitasArray) && count($fasilitasArray) > 0)
                            @foreach($fasilitasArray as $fas)
                                <div class="flex items-center gap-2 baris-fasilitas">
                                    <input type="text" name="fasilitas[]" value="{{ $fas }}" class="flex-1 bg-gray-50 border border-gray-300 rounded px-4 py-2 text-sm font-semibold focus:border-navy">
                                    <button type="button" class="btn-hapus-fasilitas text-gray-400 hover:text-red-600 p-2 transition-colors">
                                        <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center gap-2 baris-fasilitas">
                                <input type="text" name="fasilitas[]" class="flex-1 bg-gray-50 border border-gray-300 rounded px-4 py-2 text-sm font-semibold focus:border-navy">
                                <button type="button" class="btn-hapus-fasilitas text-gray-400 hover:text-red-600 p-2 transition-colors">
                                    <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-navy/5 p-6 border border-navy/10 rounded">
                    <h3 class="font-black text-navy uppercase text-sm border-b border-gray-300 pb-2 mb-2">Informasi Rute Lintasan</h3>
                    <p class="text-xs font-semibold text-navy/60 mb-6">Deskripsikan secara singkat di mana atlet akan melakukan setiap rute di dalam area venue ini.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Renang (Swim)</label>
                            <input type="text" name="rute_renang" value="{{ old('rute_renang', $venue->rute_renang) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Sepeda (Bike)</label>
                            <input type="text" name="rute_sepeda" value="{{ old('rute_sepeda', $venue->rute_sepeda) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Rute Lari (Run)</label>
                            <input type="text" name="rute_lari" value="{{ old('rute_lari', $venue->rute_lari) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Area Transisi (T1 & T2)</label>
                            <input type="text" name="area_transisi" value="{{ old('area_transisi', $venue->area_transisi) }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2 text-sm focus:border-navy">
                        </div>
                    </div>
                </div>

                <div class="border-2 border-dashed border-gray-400 rounded p-8 bg-white text-center hover:border-navy transition-colors">
                    <label class="block text-sm font-black text-navy uppercase mb-2">Tambah Foto Baru ke Galeri (Opsional)</label>
                    <p class="text-[10px] font-bold text-navy/60 mb-4">Biarkan kosong jika tidak ingin menambah foto baru (Max 3MB/foto).</p>
                    <input type="file" name="fotos[]" accept="image/*" multiple class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-black file:bg-gray-200 file:text-navy hover:file:bg-navy hover:file:text-yellow cursor-pointer mx-auto">
                </div>

                <!-- BAGIAN BAWAH -->
                <div class="flex flex-col-reverse md:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-300">
                    <button type="button" onclick="bukaModalHapus()" class="w-full md:w-auto flex items-center justify-center text-red-600 hover:text-red-800 font-black text-xs uppercase transition-colors px-4 py-3">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Venue Ini
                    </button>

                    <div class="flex w-full md:w-auto">
                        <a href="{{ route('venue.index') }}" class="flex-1 md:flex-none text-center px-6 py-3 font-bold text-sm text-navy uppercase hover:text-red-600 mr-2 transition-colors">Batal</a>
                        <button type="submit" class="flex-1 md:flex-none bg-navy text-yellow hover:bg-yellow hover:text-navy transition-colors px-8 py-3 rounded font-black text-sm uppercase shadow-md">SIMPAN PERUBAHAN</button>
                    </div>
                </div>
            </form>

            <!-- FORM HAPUS TERSEMBUNYI -->
            <form id="form-hapus-venue" action="{{ route('venue.destroy', $venue->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- MODAL POPUP HAPUS -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-navy/80 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="font-black text-navy text-xl uppercase mb-2">Hapus Venue?</h3>
            <p class="text-sm font-semibold text-navy/60 mb-8">
                Tindakan ini bersifat permanen. Seluruh data dan foto galeri yang terhubung dengan venue ini tidak dapat dikembalikan.
            </p>
            <div class="flex flex-col md:flex-row w-full gap-3">
                <button type="button" onclick="tutupModalHapus()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-navy font-bold py-3 px-4 rounded-xl transition-colors text-sm uppercase">
                    Batal
                </button>
                <button type="button" onclick="submitHapusVenue()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors text-sm uppercase">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    // Script Tambah/Hapus Fasilitas
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

    // Script Modal Konfirmasi Hapus
    function bukaModalHapus() {
        document.getElementById('modal-hapus').classList.remove('hidden');
    }

    function tutupModalHapus() {
        document.getElementById('modal-hapus').classList.add('hidden');
    }

    function submitHapusVenue() {
        document.getElementById('form-hapus-venue').submit();
    }
</script>
@endsection
