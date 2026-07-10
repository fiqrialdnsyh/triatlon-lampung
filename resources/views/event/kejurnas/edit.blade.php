@extends('layouts.main')

@section('title', 'Edit Event Kejurnas - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ route('event.kejurnas.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dasbor Kejurnas
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-2xl shadow-xl border border-gray-200">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">Edit Event Kejurnas</h2>
                <p class="text-sm text-navy/70 mb-8 pb-6 border-b border-navy/10">Modifikasi data teknis atau ubah status pendaftaran untuk menutup event secara manual.</p>

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-200 p-4 rounded-xl">
                        <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('event.kejurnas.update', $event->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Judul Kejuaraan Nasional <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ $event->judul }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Nomor Pertandingan / Kategori Lomba <span class="text-red-500">*</span></label>
                            <input type="text" name="kategori_lomba" value="{{ $event->kategori_lomba }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" value="{{ $event->tanggal_pelaksanaan }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Status Pendaftaran <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-black text-navy focus:outline-none focus:border-navy shadow-sm" required>
                                <option value="Buka" {{ $event->status == 'Buka' ? 'selected' : '' }}>BUKA</option>
                                <option value="Tutup" {{ $event->status == 'Tutup' ? 'selected' : '' }}>TUTUP</option>
                                <option value="Selesai" {{ $event->status == 'Selesai' ? 'selected' : '' }}>SELESAI</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Lokasi Pertandingan <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" value="{{ $event->lokasi }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Kuota Maksimal Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota_maksimal" value="{{ $event->kuota_maksimal }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Batas Penutupan Registrasi <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="batas_pendaftaran" name="batas_pendaftaran" value="{{ \Carbon\Carbon::parse($event->batas_pendaftaran)->format('Y-m-d\TH:i') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Detail Teknis Acara <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="5" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>{{ $event->deskripsi }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-navy/10 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-10 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        const inputTanggalEvent = document.getElementById('tanggal_pelaksanaan');
        const inputBatasDaftar = document.getElementById('batas_pendaftaran');

        inputTanggalEvent.addEventListener('change', function() {
            if (this.value) {
                inputBatasDaftar.max = this.value + 'T23:59';
            } else {
                inputBatasDaftar.removeAttribute('max');
            }
        });
    </script>
@endsection
