@extends('layouts.main')
@section('title', 'Terbitkan Kejuaraan Baru - FTI LAMPUNG')

@section('content')
<section class="bg-navy py-12 px-6 md:px-16 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('main_event.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-black uppercase tracking-wider transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            KEMBALI KE KALENDER
        </a>

        <div class="bg-cream p-8 md:p-10 shadow-2xl border-t-4 border-yellow rounded-3xl">
            <div class="mb-8 border-b border-navy/10 pb-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-navy rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div>
                    <h2 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy tracking-wide mb-1">Terbitkan Kejuaraan Baru</h2>
                    <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Sistem Sinkronisasi Master Event Otomatis</p>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-xs font-bold text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('main_event.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- SECTION 1: JALUR AKTIF -->
                <div class="bg-white p-6 border border-gray-200 rounded-2xl shadow-sm">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3 mb-1">1. Pilih Jalur Pendaftaran <span class="text-red-500">*</span></h3>
                    <p class="text-[10px] font-bold text-navy/50 mb-4 uppercase tracking-widest pl-3">Wajib mencentang salah satu atau keduanya.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <label class="jalur-check flex-1 flex items-center space-x-3 bg-gray-50 border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-navy transition-colors has-[:checked]:border-navy has-[:checked]:bg-navy/5">
                            <input type="checkbox" name="buka_jalur[]" value="Open" class="w-5 h-5 accent-navy rounded">
                            <div>
                                <span class="block text-xs font-black text-navy uppercase">Jalur Open</span>
                                <span class="block text-[9px] font-bold text-navy/50 uppercase">Untuk Individu / Umum</span>
                            </div>
                        </label>
                        <label class="jalur-check flex-1 flex items-center space-x-3 bg-gray-50 border-2 border-gray-200 rounded-xl p-4 cursor-pointer hover:border-navy transition-colors has-[:checked]:border-navy has-[:checked]:bg-navy/5">
                            <input type="checkbox" name="buka_jalur[]" value="Kejurnas" class="w-5 h-5 accent-navy rounded">
                            <div>
                                <span class="block text-xs font-black text-navy uppercase">Jalur Kejurnas</span>
                                <span class="block text-[9px] font-bold text-navy/50 uppercase">Khusus Akun Kontingen Daerah</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- SECTION 2: INFORMASI DASAR -->
                <div class="space-y-5">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">2. Informasi Dasar Acara</h3>

                    <div>
                        <label class="block text-[10px] font-black text-navy uppercase mb-1">Judul Kejuaraan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-navy transition-all" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Lokasi Venue <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-navy transition-all" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-navy transition-all" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Batas Daftar <span class="text-red-500">*</span></label>
                            <input type="date" id="batas_pendaftaran" name="batas_pendaftaran" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-navy transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-navy uppercase mb-1">Deskripsi & Syarat Ketentuan <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="4" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-navy transition-all" required></textarea>
                    </div>
                </div>

                <!-- SECTION 3: NOMOR PERLOMBAAN -->
                <div class="bg-white p-6 border border-gray-200 rounded-2xl shadow-sm mt-5 space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">3. Daftar Nomor Perlombaan</h3>
                        <button type="button" id="btn-tambah-kategori" class="inline-flex items-center gap-1 text-[10px] bg-navy text-yellow px-3 py-1.5 rounded font-black uppercase hover:bg-yellow hover:text-navy transition-colors shadow-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Nomor
                        </button>
                    </div>
                    <div id="container-kategori" class="space-y-3">
                        <div class="flex flex-col md:flex-row items-center gap-2 baris-kategori">
                            <input type="text" name="kategori_nama[]" class="w-full md:flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Misal: Standard Distance Putra" required>
                            <select name="kategori_target[]" class="w-full md:w-auto bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                                <option value="Semua">Untuk Open & Kejurnas</option>
                                <option value="Open">Hanya Jalur Open</option>
                                <option value="Kejurnas">Hanya Jalur Kejurnas</option>
                            </select>
                            <button type="button" class="btn-hapus-kategori w-full md:w-auto text-gray-400 hover:text-red-600 p-2">
                                <svg class="w-5 h-5 pointer-events-none mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: KEUANGAN & KUOTA -->
                <div class="space-y-5 border-t border-gray-300 pt-6">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">4. Keuangan & Operasional</h3>

                    <div class="bg-white p-6 border border-gray-200 rounded-2xl shadow-sm space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <label class="block text-xs font-black text-navy uppercase">Skema Biaya Pendaftaran</label>
                            <button type="button" id="btn-tambah-biaya" class="inline-flex items-center gap-1 text-[10px] bg-navy text-yellow px-3 py-1.5 rounded font-black uppercase hover:bg-yellow hover:text-navy transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Golongan
                            </button>
                        </div>
                        <div id="container-biaya" class="space-y-3">
                            <div class="flex items-center gap-2 baris-biaya">
                                <input type="text" name="nama_golongan[]" class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Kategori (Mhs/Umum)" required>
                                <input type="number" name="biaya_golongan[]" class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Nominal (0 jika gratis)" required>
                                <button type="button" class="btn-hapus-biaya text-gray-400 hover:text-red-600 p-2">
                                    <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Nama Bank <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_bank" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Nomor Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_rekening" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Atas Nama Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="atas_nama" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Kuota Maksimal Keseluruhan <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota_maksimal" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: FILE & LINK -->
                <div class="space-y-5 border-t border-gray-300 pt-6">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">5. File & Tautan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Link Grup WhatsApp <span class="text-red-500">*</span></label>
                            <input type="url" name="link_wa_grup" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="https://chat.whatsapp.com/..." required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-navy uppercase mb-1">Buku Panduan / THB (PDF, Max 10MB)</label>
                            <input type="file" name="thb_file" accept=".pdf" class="block w-full text-xs text-navy file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:font-black file:bg-gray-200 file:text-navy cursor-pointer bg-white border border-gray-300 rounded-lg p-1.5">
                        </div>
                    </div>

                    <div class="border-2 border-dashed border-navy/30 rounded-2xl p-8 bg-white text-center hover:border-yellow hover:bg-yellow/5 transition-colors">
                        <svg class="w-8 h-8 text-navy/30 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <label class="block text-sm font-black text-navy uppercase mb-2">Unggah Poster Utama Acara <span class="text-red-500">*</span></label>
                        <p class="text-[10px] font-bold text-navy/50 mb-4 uppercase tracking-widest">Rasio portrait disarankan. Maksimal 2MB.</p>
                        <input type="file" name="poster" accept="image/*" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:font-black file:bg-navy file:text-yellow hover:file:bg-yellow hover:file:text-navy cursor-pointer mx-auto" required>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-end pt-6 gap-3">
                    <a href="{{ route('main_event.index') }}" class="text-center px-6 py-4 font-black text-xs text-navy uppercase hover:text-red-600 transition-colors">Batalkan</a>
                    <button type="submit" class="bg-navy text-yellow px-10 py-4 rounded-xl font-black text-sm uppercase tracking-widest shadow-xl hover:bg-yellow hover:text-navy transition-colors">TERBITKAN ACARA</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.getElementById('btn-tambah-kategori').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'flex flex-col md:flex-row items-center gap-2 baris-kategori mt-2';
        row.innerHTML = `<input type="text" name="kategori_nama[]" class="w-full md:flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Kategori Baru" required><select name="kategori_target[]" class="w-full md:w-auto bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" required><option value="Semua">Untuk Open & Kejurnas</option><option value="Open">Hanya Jalur Open</option><option value="Kejurnas">Hanya Jalur Kejurnas</option></select><button type="button" class="btn-hapus-kategori w-full md:w-auto text-gray-400 hover:text-red-600 p-2"><svg class="w-5 h-5 pointer-events-none mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>`;
        document.getElementById('container-kategori').appendChild(row);
    });

    document.getElementById('container-kategori').addEventListener('click', function(e) {
        if(e.target.closest('.btn-hapus-kategori')) {
            const baris = document.querySelectorAll('.baris-kategori');
            if(baris.length > 1) {
                e.target.closest('.baris-kategori').remove();
            } else {
                alert('Minimal harus ada 1 nomor perlombaan.');
            }
        }
    });

    document.getElementById('btn-tambah-biaya').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 baris-biaya mt-2';
        row.innerHTML = `<input type="text" name="nama_golongan[]" class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Kategori Baru" required><input type="number" name="biaya_golongan[]" class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-bold text-navy focus:outline-none focus:border-navy" placeholder="Nominal" required><button type="button" class="btn-hapus-biaya text-gray-400 hover:text-red-600 p-2"><svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>`;
        document.getElementById('container-biaya').appendChild(row);
    });

    document.getElementById('container-biaya').addEventListener('click', function(e) {
        if(e.target.closest('.btn-hapus-biaya')) {
            const baris = document.querySelectorAll('.baris-biaya');
            if(baris.length > 1) {
                e.target.closest('.baris-biaya').remove();
            } else {
                alert('Minimal harus ada 1 skema pembiayaan. Isi dengan 0 jika gratis.');
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputTglAcara = document.getElementById('tanggal_pelaksanaan');
        const inputBatas = document.getElementById('batas_pendaftaran');

        let tzTodayDate = new Date();
        tzTodayDate.setMinutes(tzTodayDate.getMinutes() - tzTodayDate.getTimezoneOffset());
        let minDateStr = tzTodayDate.toISOString().split('T')[0];
        inputTglAcara.min = minDateStr;
        inputBatas.min = minDateStr;

        inputTglAcara.addEventListener('change', function() {
            if (this.value) {
                inputBatas.max = this.value;
                if (inputBatas.value && inputBatas.value > inputBatas.max) {
                    inputBatas.value = '';
                }
            } else {
                inputBatas.removeAttribute('max');
            }
        });

        if (inputTglAcara.value) {
            inputTglAcara.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
