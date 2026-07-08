@extends('layouts.main')

@section('title', 'Buat Event Open Tournament - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-4xl mx-auto">

            <a href="{{ route('event.open.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Event
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-2xl shadow-xl border border-gray-200">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">Buat Event Open Baru</h2>
                <p class="text-sm text-navy/70 mb-8 pb-6 border-b border-navy/10">Lengkapi data teknis pertandingan, informasi rekening pembayaran panitia, dan tautan komunikasi peserta.</p>

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-200 p-4 rounded-xl">
                        <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('event.open.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Judul Kejuaraan <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pelaksanaan" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Lokasi Pertandingan <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Kuota Maksimal Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota_maksimal" value="100" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Batas Waktu Penutupan Pendaftaran <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="batas_pendaftaran" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <h4 class="text-xs font-black text-navy uppercase tracking-widest border-b border-gray-100 pb-2 mb-2">Konfigurasi Tujuan Pembayaran & Komunikasi</h4>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Nama Bank <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_bank" placeholder="Contoh: BANK BRI" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Nomor Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_rekening" placeholder="Contoh: 1234010000" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Atas Nama Pemilik <span class="text-red-500">*</span></label>
                            <input type="text" name="atas_nama" placeholder="Contoh: FTI PROV LAMPUNG" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-navy uppercase mb-1">Link Grup WhatsApp Resmi Event <span class="text-red-500">*</span></label>
                            <input type="url" name="link_wa_grup" placeholder="https://chat.whatsapp.com/..." class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:outline-none focus:border-navy" required>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-200">
                        <h4 class="text-xs font-black text-navy uppercase tracking-widest border-b border-gray-100 pb-2 mb-4">Penggolongan Biaya Registrasi</h4>
                        <div id="container-golongan" class="space-y-3">
                            <div class="flex gap-3 items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <input type="text" name="nama_golongan[]" value="Early Bird" class="w-1/2 bg-white border border-gray-300 rounded-md px-3 py-2 text-xs font-bold text-navy" required>
                                <input type="number" name="biaya_golongan[]" value="150000" class="w-1/2 bg-white border border-gray-300 rounded-md px-3 py-2 text-xs font-bold text-navy" required>
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 p-2 hover:bg-red-50 rounded-md transition-colors" title="Hapus Golongan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="tambahBarisGolongan()" class="mt-3 text-[10px] font-black bg-gray-100 border border-gray-300 text-navy px-3 py-1.5 rounded uppercase tracking-wider">+ Tambah Golongan Biaya</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Upload Poster Resmi (Gambar)</label>
                            <input type="file" name="poster" accept="image/*" class="w-full text-xs text-navy border border-gray-300 rounded p-1 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Upload Technical Handbook (THB - PDF)</label>
                            <input type="file" name="thb_file" accept=".pdf" class="w-full text-xs text-navy border border-gray-300 rounded p-1 bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-2">Detail Teknis Acara <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" rows="5" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-navy shadow-sm" required></textarea>
                    </div>

                    <div class="pt-6 border-t border-navy/10 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-10 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                            Publikasikan Event Open
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function tambahBarisGolongan() {
            const container = document.getElementById('container-golongan');
            const row = document.createElement('div');
            row.className = "flex gap-3 items-center bg-gray-50 p-3 rounded-lg border border-gray-200";
            row.innerHTML = `
                <input type="text" name="nama_golongan[]" placeholder="Nama Golongan (Misal: Reguler)" class="w-1/2 bg-white border border-gray-300 rounded-md px-3 py-2 text-xs font-bold text-navy" required>
                <input type="number" name="biaya_golongan[]" placeholder="Nominal Biaya" class="w-1/2 bg-white border border-gray-300 rounded-md px-3 py-2 text-xs font-bold text-navy" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 p-2 hover:bg-red-50 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            `;
            container.appendChild(row);
        }
    </script>
@endsection
