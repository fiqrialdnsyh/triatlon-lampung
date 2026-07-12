@extends('layouts.main')

@section('title', 'Buat Campaign Baru - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 sm:px-8 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ url('/campaign') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                KEMBALI KE DAFTAR CAMPAIGN
            </a>

            <div class="bg-cream p-6 sm:p-8 md:p-10 rounded-[2rem] shadow-2xl">
                <h2 class="font-oswald text-2xl sm:text-3xl font-bold uppercase text-navy tracking-wide mb-2">BUAT CAMPAIGN BARU</h2>
                <p class="text-sm font-semibold text-navy/70 mb-8 border-b border-navy/10 pb-6">Pilih jenis campaign, lalu lengkapi informasi sesuai kebutuhan.</p>

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                        <p class="text-xs font-black text-red-700 uppercase mb-2">Gagal Menyimpan Campaign, Periksa Isian Berikut:</p>
                        <ul class="list-disc list-inside text-xs font-bold text-red-600 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="space-y-6">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">1. Jenis & Informasi Dasar</h3>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Jenis Campaign <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach(['Donasi' => 'Galang dana / charity', 'Kerjasama' => 'Ajakan kemitraan / sponsor', 'Campaign' => 'Kampanye konten / awareness'] as $val => $desc)
                                    <label class="tipe-option flex flex-col gap-1 bg-white border-2 border-gray-300 rounded-xl px-4 py-3 cursor-pointer hover:border-yellow transition-all shadow-sm">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="tipe" value="{{ $val }}" class="tipe-radio w-4 h-4 text-navy focus:ring-yellow" {{ old('tipe') == $val || (!old('tipe') && $val == 'Donasi') ? 'checked' : '' }} required>
                                            <span class="font-black text-navy text-sm uppercase">{{ $val }}</span>
                                        </span>
                                        <span class="text-[10px] font-semibold text-navy/50 pl-6">{{ $desc }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Judul Campaign <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: Donasi Perlengkapan Latihan Atlet Muda" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="5" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Jelaskan latar belakang, tujuan, dan detail campaign ini..." required>{{ old('deskripsi') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Poster / Gambar Sampul</label>
                            <input type="file" name="poster" accept="image/*" class="block w-full text-sm text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Tanggal Selesai (opsional)</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none">
                                <p class="text-[10px] font-bold text-navy/40 mt-1">Kosongkan jika campaign berjalan tanpa batas waktu.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Field khusus DONASI -->
                    <div id="field-donasi" class="space-y-6 pt-6 border-t border-navy/10">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">2. Pengaturan Donasi</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Target Dana (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-navy/50 text-sm font-bold">Rp</span>
                                    </div>
                                    <input type="number" name="target_dana" value="{{ old('target_dana') }}" min="1" class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-3 text-sm font-semibold text-navy focus:outline-none" placeholder="10000000">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Rekening Tujuan Donasi <span class="text-red-500">*</span></label>
                                <input type="text" name="rekening" value="{{ old('rekening') }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Bank Mandiri 114-00-1234567-8 a.n FTI Lampung">
                            </div>
                        </div>
                    </div>

                    <!-- Field khusus KERJASAMA -->
                    <div id="field-kerjasama" class="space-y-6 pt-6 border-t border-navy/10 hidden">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">2. Pengaturan Kerjasama</h3>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Link WhatsApp Kontak <span class="text-red-500">*</span></label>
                            <input type="url" name="link_wa" value="{{ old('link_wa') }}" placeholder="https://wa.me/62812xxxxxxx" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none">
                            <p class="text-[10px] font-bold text-navy/40 mt-1">Calon mitra akan diarahkan ke nomor ini saat menekan tombol "Hubungi via WhatsApp".</p>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row items-center justify-end pt-8 gap-4 border-t border-navy/10">
                        <a href="{{ url('/campaign') }}" class="w-full md:w-auto text-center font-bold text-sm text-navy uppercase px-6 py-3 hover:text-red-600 transition-colors">Batal</a>
                        <button type="submit" class="w-full md:w-auto bg-yellow text-navy px-8 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-navy hover:text-yellow transition-colors shadow-lg transform hover:-translate-y-1">
                            SIMPAN & PUBLIKASIKAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.tipe-radio');
            const fieldDonasi = document.getElementById('field-donasi');
            const fieldKerjasama = document.getElementById('field-kerjasama');
            const inputTargetDana = document.querySelector('input[name="target_dana"]');
            const inputRekening = document.querySelector('input[name="rekening"]');
            const inputLinkWa = document.querySelector('input[name="link_wa"]');

            function updateFields(tipe) {
                // reset dulu semua required & visibility
                fieldDonasi.classList.add('hidden');
                fieldKerjasama.classList.add('hidden');
                inputTargetDana.required = false;
                inputRekening.required = false;
                inputLinkWa.required = false;

                if (tipe === 'Donasi') {
                    fieldDonasi.classList.remove('hidden');
                    inputTargetDana.required = true;
                    inputRekening.required = true;
                } else if (tipe === 'Kerjasama') {
                    fieldKerjasama.classList.remove('hidden');
                    inputLinkWa.required = true;
                }
                // tipe 'Campaign' -> kedua blok tetap tersembunyi, tidak ada field wajib tambahan

                // styling kartu radio yang aktif
                document.querySelectorAll('.tipe-option').forEach(el => {
                    const r = el.querySelector('.tipe-radio');
                    el.classList.toggle('border-navy', r.checked);
                    el.classList.toggle('bg-yellow/10', r.checked);
                });
            }

            radios.forEach(r => r.addEventListener('change', () => updateFields(r.value)));

            // Set kondisi awal saat halaman dimuat (misal setelah validasi gagal / old input)
            const checked = document.querySelector('.tipe-radio:checked');
            updateFields(checked ? checked.value : 'Donasi');
        });
    </script>
@endsection
