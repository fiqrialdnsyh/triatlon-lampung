@extends('layouts.main')

@section('title', 'Kelola Data SDM Olahraga - FTI LAMPUNG')

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen relative">
        <div class="max-w-7xl mx-auto relative z-10">

            <a href="{{ url('/personil') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Direktori SDM
            </a>

            <div class="bg-cream p-6 md:p-10 rounded-2xl shadow-xl">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-navy/10 pb-6">
                    <div>
                        <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-1">Manajemen Kelola SDM</h2>
                        <p class="text-xs font-bold text-navy/50 uppercase tracking-widest">Pusat pembaruan profil data Atlet, Pelatih, & Wasit Wilayah Lampung.</p>
                    </div>
                    <a href="{{ url('/personil/create') }}" class="mt-4 md:mt-0 bg-navy text-yellow px-5 py-3 font-black text-xs uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-md">
                        + REGISTRASI SDM
                    </a>
                </div>

                <div class="flex gap-2 mb-8 max-w-sm">
                    <button onclick="switchManageTab('atlet')" id="btn-m-atlet" class="flex-1 py-2 text-center rounded-lg font-black text-xs uppercase tracking-wider transition-colors bg-navy text-yellow">Atlet</button>
                    <button onclick="switchManageTab('pelatih')" id="btn-m-pelatih" class="flex-1 py-2 text-center rounded-lg font-black text-xs uppercase tracking-wider transition-colors bg-white border border-gray-300 text-navy/40">Pelatih</button>
                    <button onclick="switchManageTab('wasit')" id="btn-m-wasit" class="flex-1 py-2 text-center rounded-lg font-black text-xs uppercase tracking-wider transition-colors bg-white border border-gray-300 text-navy/40">Wasit</button>
                </div>

                @php
                    $wilayahLampung = ['Bandar Lampung', 'Metro', 'Pesawaran', 'Pringsewu', 'Tanggamus', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Utara', 'Lampung Barat', 'Lampung Timur', 'Way Kanan', 'Tulang Bawang', 'Tulang Bawang Barat', 'Mesuji', 'Pesisir Barat'];
                @endphp

                <!-- SECTION ATLET -->
                <div id="m-section-atlet" class="m-tab-content block space-y-4">
                    @forelse($atlets as $a)
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
                            <form action="{{ route('personil.update', $a->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1 w-full">
                                @csrf @method('PUT')
                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Nama Lengkap</label>
                                    <input type="text" name="nama" value="{{ $a->nama }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Asal Daerah</label>
                                    <select name="asal_daerah" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-2 py-2 text-xs font-bold text-navy" required>
                                        @foreach($wilayahLampung as $w)
                                            <option value="{{ $w }}" {{ $a->asal_daerah == $w ? 'selected' : '' }}>{{ $w }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">No WhatsApp</label>
                                    <input type="text" name="kontak" value="{{ $a->kontak }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Tempat Tanggal Lahir</label>
                                    <input type="text" name="ttl" value="{{ $a->ttl }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Usia / Umur</label>
                                    <input type="number" name="umur" value="{{ $a->umur }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy">
                                </div>

                                <!-- Opsi Upload Foto Atlet -->
                                <div class="md:col-span-3">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Update Foto Profil (Opsional)</label>
                                    <input type="file" name="foto" accept="image/*" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded-lg p-1 bg-gray-50">
                                </div>

                                <div class="md:col-span-4 flex justify-end mt-2">
                                    <button type="submit" class="bg-navy text-yellow px-6 py-2 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors">Simpan Perubahan</button>
                                </div>
                            </form>
                            <!-- Tombol Hapus Atlet -->
                            <form action="{{ route('personil.destroy', $a->id) }}" method="POST" id="form-delete-atlet-{{ $a->id }}" class="w-full lg:w-auto pt-2 lg:pt-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="bukaModalHapus('form-delete-atlet-{{ $a->id }}')" class="w-full lg:w-auto bg-red-500 hover:bg-red-600 transition-colors text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data atlet.</p>
                    @endforelse
                </div>

                <!-- SECTION PELATIH -->
                <div id="m-section-pelatih" class="m-tab-content hidden space-y-4">
                    @forelse($pelatihs as $p)
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
                            <form action="{{ route('personil.update', $p->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1 w-full">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Nama Pelatih</label>
                                    <input type="text" name="nama" value="{{ $p->nama }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Asal Wilayah</label>
                                    <select name="asal_daerah" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-2 py-2 text-xs font-bold text-navy" required>
                                        @foreach($wilayahLampung as $w)
                                            <option value="{{ $w }}" {{ $p->asal_daerah == $w ? 'selected' : '' }}>{{ $w }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Kualifikasi Lisensi</label>
                                    <input type="text" name="tingkat_lisensi" value="{{ $p->tingkat_lisensi }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">No WhatsApp</label>
                                    <input type="text" name="kontak" value="{{ $p->kontak }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy">
                                </div>

                                <!-- Opsi Upload Tambahan Pelatih -->
                                <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Update Foto Profil (Opsional)</label>
                                        <input type="file" name="foto" accept="image/*" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded-lg p-1 bg-gray-50">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Update Dokumen Lisensi (Opsional - PDF/Gambar)</label>
                                        <input type="file" name="file_lisensi" accept=".pdf,image/*" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded-lg p-1 bg-gray-50">
                                    </div>
                                </div>

                                <div class="md:col-span-4 flex justify-end mt-2">
                                    <button type="submit" class="bg-navy text-yellow px-6 py-2 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors">Simpan Perubahan</button>
                                </div>
                            </form>
                            <!-- Tombol Hapus Pelatih -->
                            <form action="{{ route('personil.destroy', $p->id) }}" method="POST" id="form-delete-pelatih-{{ $p->id }}" class="w-full lg:w-auto pt-2 lg:pt-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="bukaModalHapus('form-delete-pelatih-{{ $p->id }}')" class="w-full lg:w-auto bg-red-500 hover:bg-red-600 transition-colors text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data pelatih.</p>
                    @endforelse
                </div>

                <!-- SECTION WASIT -->
                <div id="m-section-wasit" class="m-tab-content hidden space-y-4">
                    @forelse($wasits as $w)
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
                            <form action="{{ route('personil.update', $w->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 flex-1 w-full">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Nama Wasit</label>
                                    <input type="text" name="nama" value="{{ $w->nama }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Wilayah Tugas</label>
                                    <select name="asal_daerah" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-2 py-2 text-xs font-bold text-navy" required>
                                        @foreach($wilayahLampung as $wly)
                                            <option value="{{ $wly }}" {{ $w->asal_daerah == $wly ? 'selected' : '' }}>{{ $wly }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Lisensi Korps</label>
                                    <input type="text" name="tingkat_lisensi" value="{{ $w->tingkat_lisensi }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">No WhatsApp</label>
                                    <input type="text" name="kontak" value="{{ $w->kontak }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy">
                                </div>

                                <!-- Opsi Upload Tambahan Wasit -->
                                <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Update Foto Profil (Opsional)</label>
                                        <input type="file" name="foto" accept="image/*" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded-lg p-1 bg-gray-50">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Update Dokumen Lisensi (Opsional - PDF/Gambar)</label>
                                        <input type="file" name="file_lisensi" accept=".pdf,image/*" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded-lg p-1 bg-gray-50">
                                    </div>
                                </div>

                                <div class="md:col-span-4 flex justify-end mt-2">
                                    <button type="submit" class="bg-navy text-yellow px-6 py-2 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors">Simpan Perubahan</button>
                                </div>
                            </form>
                            <!-- Tombol Hapus Wasit -->
                            <form action="{{ route('personil.destroy', $w->id) }}" method="POST" id="form-delete-wasit-{{ $w->id }}" class="w-full lg:w-auto pt-2 lg:pt-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="bukaModalHapus('form-delete-wasit-{{ $w->id }}')" class="w-full lg:w-auto bg-red-500 hover:bg-red-600 transition-colors text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data wasit.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- MODAL POPUP HAPUS -->
        <div id="modal-hapus" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm transition-opacity" onclick="tutupModalHapus()"></div>
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl relative z-10 transform scale-95 transition-transform duration-300 text-center" id="modal-content">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="font-black text-navy text-xl uppercase mb-2">Hapus Data SDM?</h3>
                <p class="text-sm font-semibold text-navy/60 mb-8">
                    Tindakan ini bersifat permanen. Seluruh data profil dan dokumen pendukung personil ini akan dihapus.
                </p>
                <div class="flex flex-col md:flex-row w-full gap-3">
                    <button type="button" onclick="tutupModalHapus()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-navy font-bold py-3 px-4 rounded-xl transition-colors text-sm uppercase">
                        Batal
                    </button>
                    <button type="button" onclick="submitHapusSDM()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors text-sm uppercase">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        function switchManageTab(role) {
            document.querySelectorAll('.m-tab-content').forEach(el => el.classList.replace('block', 'hidden'));

            const btnA = document.getElementById('btn-m-atlet');
            const btnP = document.getElementById('btn-m-pelatih');
            const btnW = document.getElementById('btn-m-wasit');

            [btnA, btnP, btnW].forEach(btn => {
                btn.classList.remove('bg-navy', 'text-yellow');
                btn.classList.add('bg-white', 'text-navy/40', 'border', 'border-gray-300');
            });

            document.getElementById(`m-section-${role}`).classList.replace('hidden', 'block');

            const activeBtn = document.getElementById(`btn-m-${role}`);
            activeBtn.classList.remove('bg-white', 'text-navy/40', 'border', 'border-gray-300');
            activeBtn.classList.add('bg-navy', 'text-yellow');
        }

        // Variabel untuk menyimpan ID form yang akan dihapus
        let targetFormId = null;

        // Script Modal Konfirmasi Hapus
        function bukaModalHapus(formId) {
            targetFormId = formId;
            const modal = document.getElementById('modal-hapus');
            const content = document.getElementById('modal-content');
            modal.classList.remove('hidden');

            // Animasi transisi
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function tutupModalHapus() {
            const modal = document.getElementById('modal-hapus');
            const content = document.getElementById('modal-content');

            content.classList.remove('scale-100');
            content.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                targetFormId = null;
            }, 200);
        }

        function submitHapusSDM() {
            if (targetFormId) {
                document.getElementById(targetFormId).submit();
            }
        }
    </script>
@endsection
