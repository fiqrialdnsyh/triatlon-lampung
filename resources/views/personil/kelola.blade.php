@extends('layouts.main')

@section('title', 'Kelola Data SDM Olahraga - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-7xl mx-auto">

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
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-navy text-yellow py-2 rounded-lg font-black text-xs uppercase tracking-wider">Simpan</button>
                                </div>
                            </form>
                            <form action="{{ route('personil.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus data atlet ini?')" class="w-full lg:w-auto pt-2 lg:pt-4">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full lg:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data atlet.</p>
                    @endforelse
                </div>

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
                                <div class="md:col-span-4 flex justify-end">
                                    <button type="submit" class="bg-navy text-yellow px-6 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Simpan Perubahan</button>
                                </div>
                            </form>
                            <form action="{{ route('personil.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pelatih ini?')" class="w-full lg:w-auto pt-2 lg:pt-4">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full lg:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data pelatih.</p>
                    @endforelse
                </div>

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
                                <div class="md:col-span-4 flex justify-end">
                                    <button type="submit" class="bg-navy text-yellow px-6 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Simpan Perubahan</button>
                                </div>
                            </form>
                            <form action="{{ route('personil.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Hapus data wasit ini?')" class="w-full lg:w-auto pt-2 lg:pt-4">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full lg:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase tracking-wider">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs font-bold text-navy/40 uppercase text-center p-8">Belum ada data wasit.</p>
                    @endforelse
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
    </script>
@endsection
