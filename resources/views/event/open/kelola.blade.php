@extends('layouts.main')

@section('title', 'Kelola Event Open - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <a href="{{ route('event.open.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Event
            </a>

            <div class="bg-cream p-6 md:p-10 rounded-2xl shadow-xl">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl">
                        <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-navy/10 pb-6">
                    <div>
                        <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-1">MANAJEMEN KELOLA EVENT</h2>
                        <p class="text-xs font-bold text-navy/50 uppercase tracking-widest">Pusat modifikasi jadwal, kuota, biaya, dan status turnamen terbuka.</p>
                    </div>
                    <a href="{{ route('event.open.create') }}" class="mt-4 md:mt-0 bg-navy text-yellow px-5 py-3 font-black text-xs uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-md">
                        + BUAT EVENT BARU
                    </a>
                </div>

                <div class="space-y-6">
                    @forelse($events as $event)
                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col gap-4 relative">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <h3 class="font-black text-navy text-lg uppercase tracking-wide pr-24">{{ $event->judul }}</h3>
                            </div>

                            <form action="{{ route('event.open.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-4 w-full">
                                @csrf @method('PUT')

                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Ubah Judul Event</label>
                                    <input type="text" name="judul" value="{{ $event->judul }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Tanggal Pelaksanaan</label>
                                    <input type="date" name="tanggal_pelaksanaan" value="{{ $event->tanggal_pelaksanaan }}" min="{{ date('Y-m-d') }}" onchange="sinkronkanBatasTanggal(this)" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Status Pendaftaran</label>
                                    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                        <option value="Buka" {{ $event->status == 'Buka' ? 'selected' : '' }}>Buka</option>
                                        <option value="Tutup" {{ $event->status == 'Tutup' ? 'selected' : '' }}>Tutup</option>
                                        <option value="Selesai" {{ $event->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Lokasi Pertandingan</label>
                                    <input type="text" name="lokasi" value="{{ $event->lokasi }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Kategori / Nomor Pertandingan</label>
                                    <input type="text" name="kategori_lomba" value="{{ $event->kategori_lomba }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Batas Waktu Tutup</label>
                                    <input type="datetime-local" name="batas_pendaftaran" value="{{ \Carbon\Carbon::parse($event->batas_pendaftaran)->format('Y-m-d\TH:i') }}" min="{{ date('Y-m-d\TH:i') }}" max="{{ $event->tanggal_pelaksanaan }}T23:59" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Kuota Total</label>
                                    <input type="number" name="kuota_maksimal" value="{{ $event->kuota_maksimal }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                </div>

                                <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-4 gap-4 bg-gray-50 p-4 border border-gray-200 rounded-xl mt-2">
                                    <div class="md:col-span-4">
                                        <h4 class="text-[10px] font-black text-navy uppercase tracking-widest border-b border-gray-200 pb-2">Informasi Rekening & Komunikasi</h4>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase mb-1">Nama Bank</label>
                                        <input type="text" name="nama_bank" value="{{ $event->nama_bank }}" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase mb-1">No. Rekening</label>
                                        <input type="number" name="nomor_rekening" value="{{ $event->nomor_rekening }}" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase mb-1">Atas Nama</label>
                                        <input type="text" name="atas_nama" value="{{ $event->atas_nama }}" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase mb-1">Link WA Grup</label>
                                        <input type="url" name="link_wa_grup" value="{{ $event->link_wa_grup }}" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-bold text-navy" required>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Ganti Poster (Abaikan jika tetap)</label>
                                    <input type="file" name="poster" accept="image/*" class="w-full text-[10px] p-0.5 border border-gray-300 bg-gray-50 rounded-lg cursor-pointer">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Ganti THB PDF (Abaikan jika tetap)</label>
                                    <input type="file" name="thb_file" accept=".pdf" class="w-full text-[10px] p-0.5 border border-gray-300 bg-gray-50 rounded-lg cursor-pointer">
                                </div>

                                <div class="md:col-span-4">
                                    <label class="block text-[9px] font-black text-navy/40 uppercase mb-1">Perbarui Regulasi & Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy" required>{{ $event->deskripsi }}</textarea>
                                </div>

                                <div class="md:col-span-4 flex justify-end gap-3 mt-2 border-t border-gray-100 pt-4">
                                    <button type="submit" class="bg-navy text-yellow px-8 py-2.5 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors">
                                        Simpan Perubahan Data
                                    </button>
                                </div>
                            </form>

                            <form action="{{ route('event.open.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus turnamen ini secara permanen?')" class="absolute top-6 right-6">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] bg-red-50 text-red-500 border border-red-200 px-3 py-1.5 rounded hover:bg-red-500 hover:text-white font-black uppercase transition-colors pointer-events-auto">
                                    Hapus Event
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="bg-white border-2 border-dashed border-gray-300 p-12 rounded-[2rem] text-center">
                            <p class="font-black text-navy/40 uppercase text-sm tracking-wider">Belum ada histori event Open Tournament.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    <script>
        function sinkronkanBatasTanggal(elementInput) {
            // Mencari elemen form terdekat dari input tanggal yang sedang diubah
            const formInduk = elementInput.closest('form');
            const inputBatasDaftar = formInduk.querySelector('input[name="batas_pendaftaran"]');

            if (elementInput.value) {
                inputBatasDaftar.max = elementInput.value + 'T23:59';
            } else {
                inputBatasDaftar.removeAttribute('max');
            }
        }
    </script>
@endsection
