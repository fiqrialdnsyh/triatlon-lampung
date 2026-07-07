@extends('layouts.main')

@section('title', 'Tambah Pelatihan - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ url('/pelatihan') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE DAFTAR PELATIHAN
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">BUAT PROGRAM PELATIHAN BARU</h2>
                <p class="text-sm font-semibold text-navy/70 mb-8 border-b border-navy/10 pb-6">Lengkapi informasi dasar dan atur formulir pendaftaran untuk peserta.</p>

                <form action="{{ route('pelatihan.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="space-y-6">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">1. Informasi Dasar Pelatihan</h3>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Judul Pelatihan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: Kursus Pelatih Tingkat Dasar (Level 1)" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Deskripsi & Ketentuan <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="4" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Jelaskan materi, syarat peserta, dan detail kegiatan pelatihan..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Tempat Pelaksanaan <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: GSG Kampus ITERA" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Batas Kuota Peserta <span class="text-red-500">*</span></label>
                                <input type="number" name="kuota" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: 50" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Pelaksanaan <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_pelaksanaan" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm cursor-pointer" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Batas Daftar <span class="text-red-500">*</span></label>
                                <input type="date" name="batas_pendaftaran" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm cursor-pointer" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Status <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="status" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm appearance-none cursor-pointer" required>
                                        <option value="Buka">Buka</option>
                                        <option value="Tutup">Tutup</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-navy/10">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">2. Pengaturan Biaya & Pembayaran</h3>

                        <div class="bg-white/50 p-6 rounded-2xl border border-gray-300 space-y-4">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-3">
                                <p class="text-[11px] font-bold text-navy/60 uppercase tracking-wider">Tentukan Biaya Berdasarkan Golongan</p>
                                <button type="button" id="btn-tambah-biaya" class="inline-flex items-center justify-center bg-navy text-yellow px-4 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-yellow hover:text-navy transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Golongan
                                </button>
                            </div>

                            <div id="container-biaya" class="space-y-3">
                                <div class="flex items-center gap-3 baris-biaya">
                                    <div class="flex-1">
                                        <input type="text" name="nama_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" placeholder="Nama Golongan" required>
                                    </div>
                                    <div class="flex-1 relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-navy/50 text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="biaya_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" placeholder="0" required>
                                    </div>
                                    <button type="button" class="btn-hapus-biaya bg-red-100 text-red-500 hover:bg-red-500 hover:text-white p-2.5 rounded-lg transition-colors" title="Hapus baris ini">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Informasi Rekening Pembayaran <span class="text-red-500">*</span></label>
                            <input type="text" name="rekening" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow focus:border-yellow transition-all shadow-sm" placeholder="Contoh: Bank Mandiri 114-00-1234567-8 a.n FOPI Lampung" required>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-navy/10">
                        <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3">3. Konfigurasi Formulir Peserta</h3>
                        <p class="text-xs font-semibold text-navy/70 mb-4">Centang data apa saja yang wajib diisi oleh peserta saat mendaftar pelatihan ini.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center space-x-3 p-4 bg-gray-100 border border-gray-200 rounded-xl opacity-70 cursor-not-allowed">
                                <input type="checkbox" checked disabled class="w-5 h-5 text-gray-500 rounded border-gray-300">
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Data Diri Dasar</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Nama, Email, Usia, Jenis Kelamin, Asal Daerah</span>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-4 bg-gray-100 border border-gray-200 rounded-xl opacity-70 cursor-not-allowed">
                                <input type="checkbox" checked disabled class="w-5 h-5 text-gray-500 rounded border-gray-300">
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Upload Bukti Pembayaran</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Wajib untuk proses verifikasi admin</span>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-4 bg-white border border-gray-300 rounded-xl cursor-pointer hover:border-yellow transition-all shadow-sm">
                                <input type="checkbox" name="form_pekerjaan" value="1" class="w-5 h-5 text-navy focus:ring-yellow rounded border-gray-300" checked>
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Pekerjaan / Profesi</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Meminta input profesi peserta saat ini</span>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-4 bg-white border border-gray-300 rounded-xl cursor-pointer hover:border-yellow transition-all shadow-sm">
                                <input type="checkbox" name="form_pengalaman" value="1" class="w-5 h-5 text-navy focus:ring-yellow rounded border-gray-300" checked>
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Pengalaman Melatih</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Meminta input latar belakang cabor yang pernah dilatih</span>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-4 bg-white border border-gray-300 rounded-xl cursor-pointer hover:border-yellow transition-all shadow-sm">
                                <input type="checkbox" name="form_ukuran_baju" value="1" class="w-5 h-5 text-navy focus:ring-yellow rounded border-gray-300" checked>
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Ukuran Baju (S, M, L, XL, XXL)</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Aktifkan jika pelatihan ini memberikan seragam</span>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-4 bg-white border border-gray-300 rounded-xl cursor-pointer hover:border-yellow transition-all shadow-sm">
                                <input type="checkbox" name="form_surat_rekomendasi" value="1" class="w-5 h-5 text-navy focus:ring-yellow rounded border-gray-300">
                                <div>
                                    <span class="block text-sm font-bold text-navy uppercase">Surat Rekomendasi MGMP</span>
                                    <span class="block text-[10px] font-semibold text-navy/60">Field upload file PDF untuk jalur khusus guru</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row items-center justify-end pt-8 gap-4">
                        <a href="{{ url('/pelatihan') }}" class="w-full md:w-auto text-center font-bold text-sm text-navy uppercase px-6 py-3 hover:text-red-600 transition-colors">Batal</a>
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
            const containerBiaya = document.getElementById('container-biaya');
            const btnTambahBiaya = document.getElementById('btn-tambah-biaya');

            function attachDeleteEvent(button) {
                button.addEventListener('click', function() {
                    if (containerBiaya.querySelectorAll('.baris-biaya').length > 1) {
                        this.closest('.baris-biaya').remove();
                    } else {
                        alert('Minimal harus ada 1 golongan biaya. Ubah nominal menjadi 0 jika gratis.');
                    }
                });
            }

            attachDeleteEvent(document.querySelector('.btn-hapus-biaya'));

            btnTambahBiaya.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'flex items-center gap-3 baris-biaya mt-3';
                newRow.innerHTML = `
                    <div class="flex-1">
                        <input type="text" name="nama_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" placeholder="Nama Golongan" required>
                    </div>
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-navy/50 text-sm font-bold">Rp</span>
                        </div>
                        <input type="number" name="biaya_golongan[]" class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-4 py-2.5 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" placeholder="0" required>
                    </div>
                    <button type="button" class="btn-hapus-biaya bg-red-100 text-red-500 hover:bg-red-500 hover:text-white p-2.5 rounded-lg transition-colors" title="Hapus baris ini">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                `;
                containerBiaya.appendChild(newRow);
                attachDeleteEvent(newRow.querySelector('.btn-hapus-biaya'));
            });
        });
    </script>
@endsection
