@extends('layouts.main')

@section('title', 'Edit Data Pengurus - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <a href="{{ route('pengurus.kelola') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE MANAJEMEN KELOLA
            </a>

            <div class="bg-cream p-6 md:p-10 rounded-[2rem] shadow-2xl">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">EDIT DATA ANGGOTA PENGURUS</h2>
                <p class="text-sm font-semibold text-navy/70 mb-8 pb-6 border-b border-navy/10">Lakukan perubahan informasi data personal kepengurusan di bawah ini.</p>

                <form action="{{ route('pengurus.update', $pengurus->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Kategori / Hierarki</label>
                        <select name="kategori" id="edit-kategori" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold text-navy focus:outline-none focus:border-yellow" required>
                            <option value="Dewan" {{ $pengurus->kategori == 'Dewan' ? 'selected' : '' }}>Dewan Pembina / Penasihat</option>
                            <option value="Inti" {{ $pengurus->kategori == 'Inti' ? 'selected' : '' }}>Pengurus Harian (Inti)</option>
                            <option value="Bidang" {{ $pengurus->kategori == 'Bidang' ? 'selected' : '' }}>Ketua / Anggota Bidang</option>
                            <option value="Cabang" {{ $pengurus->kategori == 'Cabang' ? 'selected' : '' }}>Pengurus Cabang Daerah</option>
                        </select>
                    </div>

                    <div id="edit-blok-daerah" class="{{ $pengurus->kategori == 'Cabang' ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Nama Daerah Kabupaten / Kota</label>
                        <input type="text" name="nama_daerah" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow" value="{{ $pengurus->nama_daerah }}" placeholder="Contoh: Bandar Lampung">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Posisi / Nama Jabatan</label>
                        <input type="text" name="jabatan" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow" value="{{ $pengurus->jabatan }}" placeholder="Contoh: Ketua Umum / Anggota" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5">Nama Lengkap Pengurus</label>
                        <input type="text" name="nama" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow" value="{{ $pengurus->nama }}" placeholder="Nama Lengkap & Gelar" required>
                    </div>

                    <div id="edit-blok-keterangan" class="{{ in_array($pengurus->kategori, ['Bidang', 'Cabang']) ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1.5" id="label-keterangan">
                            {{ $pengurus->kategori == 'Bidang' ? 'Nama Bidang (Grup)' : 'Alamat Sekretariat Cabang' }}
                        </label>
                        <input type="text" name="keterangan" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow" value="{{ $pengurus->keterangan }}">
                    </div>

                    <div class="pt-6 border-t border-navy/10 flex justify-end gap-3">
                        <a href="{{ route('pengurus.kelola') }}" class="bg-gray-200 text-navy px-6 py-3 rounded-xl font-bold text-sm uppercase">Batal</a>
                        <button type="submit" class="bg-navy text-yellow px-8 py-3 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script>
        const selectKategori = document.getElementById('edit-kategori');
        const blokDaerah = document.getElementById('edit-blok-daerah');
        const blokKeterangan = document.getElementById('edit-blok-keterangan');
        const labelKeterangan = document.getElementById('label-keterangan');

        selectKategori.addEventListener('change', function() {
            if (this.value === 'Cabang') {
                blokDaerah.classList.remove('hidden');
                blokKeterangan.classList.remove('hidden');
                labelKeterangan.textContent = 'Alamat Sekretariat Cabang';
            } else if (this.value === 'Bidang') {
                blokDaerah.classList.add('hidden');
                blokKeterangan.classList.remove('hidden');
                labelKeterangan.textContent = 'Nama Bidang (Grup)';
            } else {
                blokDaerah.classList.add('hidden');
                blokKeterangan.classList.add('hidden');
            }
        });
    </script>
@endsection
