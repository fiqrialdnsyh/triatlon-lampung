@extends('layouts.main')

@section('title', 'Edit Pelatihan - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ url('/pelatihan') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE DAFTAR PELATIHAN
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">EDIT PROGRAM PELATIHAN</h2>
                <p class="text-sm font-semibold text-navy/70 mb-8 border-b border-navy/10 pb-6">Perbarui informasi, status pendaftaran, atau biaya pelatihan.</p>

                <form action="{{ route('pelatihan.update', $pelatihan->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

                    <div class="space-y-6">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">1. Informasi Dasar</h3>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Judul Pelatihan</label>
                            <input type="text" name="judul" value="{{ $pelatihan->judul }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>{{ $pelatihan->deskripsi }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Pelaksanaan</label>
                                <input type="date" name="tanggal_pelaksanaan" value="{{ $pelatihan->tanggal_pelaksanaan }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Batas Daftar</label>
                                <input type="date" name="batas_pendaftaran" value="{{ $pelatihan->batas_pendaftaran }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Status Pendaftaran</label>
                                <select name="status" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>
                                    <option value="Buka" {{ $pelatihan->status == 'Buka' ? 'selected' : '' }}>Buka</option>
                                    <option value="Tutup" {{ $pelatihan->status == 'Tutup' ? 'selected' : '' }}>Tutup (Paksa Tutup)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-navy/10">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">2. Pengaturan Biaya</h3>
                        <div class="bg-white/50 p-6 rounded-2xl border border-gray-300 space-y-4">
                            <div id="container-biaya" class="space-y-3">
                                @if(is_array($pelatihan->biaya) && count($pelatihan->biaya) > 0)
                                    @foreach($pelatihan->biaya as $biaya)
                                        <div class="flex items-center gap-3 baris-biaya">
                                            <div class="flex-1">
                                                <input type="text" name="nama_golongan[]" value="{{ $biaya['nama'] }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-semibold text-navy" required>
                                            </div>
                                            <div class="flex-1 relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-navy/50 text-sm font-bold">Rp</span></div>
                                                <input type="number" name="biaya_golongan[]" value="{{ $biaya['nominal'] }}" class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm font-semibold text-navy" required>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center gap-3 baris-biaya">
                                        <div class="flex-1"><input type="text" name="nama_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5" placeholder="Nama Golongan" required></div>
                                        <div class="flex-1 relative"><input type="number" name="biaya_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5" placeholder="0" required></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Rekening Pembayaran</label>
                            <input type="text" name="rekening" value="{{ $pelatihan->rekening }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 gap-4 border-t border-navy/10">
                        <a href="{{ url('/pelatihan') }}" class="text-center font-bold text-sm text-navy uppercase px-6 py-3 hover:text-red-600 transition-colors">Batal</a>
                        <button type="submit" class="bg-yellow text-navy px-8 py-4 rounded-xl font-black text-sm uppercase shadow-lg transform hover:-translate-y-1">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
