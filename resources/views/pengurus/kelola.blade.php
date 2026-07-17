@extends('layouts.main')

@section('title', 'Kelola Pengurus - FTI LAMPUNG')

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen relative">
        <div class="max-w-6xl mx-auto relative z-10">

            <a href="{{ url('/pengurus') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE STRUKTUR PENGURUS
            </a>

            <div class="bg-cream p-6 md:p-10 rounded-[2rem] shadow-2xl">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-navy/10 pb-6">
                    <div>
                        <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-1">MANAJEMEN KELOLA PENGURUS</h2>
                        <p class="text-sm font-semibold text-navy/70">Ubah teks jabatan/nama lalu simpan, atau perbarui dokumen SK secara langsung.</p>
                    </div>
                    <a href="{{ url('/pengurus/create') }}" class="mt-4 md:mt-0 bg-navy text-yellow px-5 py-3 font-black text-xs uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-md">
                        + INPUT DATA BARU
                    </a>
                </div>

                <!-- PILIHAN MODE FORM -->
                <div class="flex flex-col md:flex-row gap-4 mb-8">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="tipe_form" value="provinsi" class="peer hidden" checked onchange="toggleFormMode()">
                        <div class="text-center p-4 rounded-xl border-2 border-gray-300 bg-white text-navy/50 font-black uppercase tracking-wider peer-checked:border-yellow peer-checked:bg-yellow/10 peer-checked:text-navy transition-all shadow-sm">
                            Kelola Pengurus Provinsi
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="tipe_form" value="cabang" class="peer hidden" onchange="toggleFormMode()">
                        <div class="text-center p-4 rounded-xl border-2 border-gray-300 bg-white text-navy/50 font-black uppercase tracking-wider peer-checked:border-yellow peer-checked:bg-yellow/10 peer-checked:text-navy transition-all shadow-sm">
                            Kelola Pengurus Cabang Daerah
                        </div>
                    </label>
                </div>

                <!-- ======================================================= -->
                <!-- MODE 1: KELOLA PROVINSI                                 -->
                <!-- ======================================================= -->
                <div id="form-provinsi" class="space-y-8">

                    <!-- KOTAK KHUSUS REVISI SK PROVINSI -->
                    @php
                        $sampleProv = $inti->first() ?? $dewan->first() ?? $bidang->first();
                    @endphp
                    <div class="bg-yellow/10 border-2 border-yellow/30 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="font-black text-navy uppercase text-sm mb-1">Revisi SK Provinsi (Global)</h3>
                            <p class="text-[10px] font-bold text-navy/60">Unggah PDF baru di sini untuk mengganti SK seluruh jajaran Provinsi secara otomatis.</p>
                        </div>
                        @if($sampleProv)
                            <form action="{{ route('pengurus.update', $sampleProv->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                                @csrf @method('PUT')
                                <input type="hidden" name="form_khusus_sk" value="1">
                                <input type="file" name="file_sk" accept=".pdf" class="w-full text-[10px] text-navy file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-white file:text-navy border border-gray-300 rounded-lg p-1 bg-white" required>
                                <button type="submit" class="w-full sm:w-auto bg-navy text-yellow px-5 py-2.5 rounded-lg font-black text-[10px] uppercase shadow-md hover:bg-yellow hover:text-navy transition-colors shrink-0">Upload SK</button>
                            </form>
                        @else
                            <span class="text-[10px] font-bold text-red-500 bg-white px-3 py-1 rounded">Input data pengurus provinsi terlebih dahulu</span>
                        @endif
                    </div>

                    <!-- BLOK A: DEWAN -->
                    <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                        <h3 class="font-black text-navy uppercase text-lg border-b border-gray-200 pb-2 mb-4">1. Dewan Pembina & Penasihat</h3>
                        <div class="space-y-3">
                            @forelse($dewan as $d)
                                <div class="flex flex-col md:flex-row gap-2 items-center bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                    <form action="{{ route('pengurus.update', $d->id) }}" method="POST" class="flex flex-col md:flex-row gap-2 flex-1 w-full">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="kategori" value="Dewan">
                                        <select name="jabatan" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                            <option value="Pelindung" {{ $d->jabatan == 'Pelindung' ? 'selected' : '' }}>Pelindung</option>
                                            <option value="Dewan Kehormatan" {{ $d->jabatan == 'Dewan Kehormatan' ? 'selected' : '' }}>Dewan Kehormatan</option>
                                            <option value="Dewan Pembina" {{ $d->jabatan == 'Dewan Pembina' ? 'selected' : '' }}>Dewan Pembina</option>
                                            <option value="Dewan Penasihat" {{ $d->jabatan == 'Dewan Penasihat' ? 'selected' : '' }}>Dewan Penasihat</option>
                                        </select>
                                        <input type="text" name="nama" value="{{ $d->nama !== '-' ? $d->nama : '' }}" class="w-full md:w-2/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama / Instansi">
                                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-yellow hover:text-navy transition-colors">Simpan</button>
                                    </form>
                                    <form action="{{ route('pengurus.destroy', $d->id) }}" method="POST" id="form-delete-dewan-{{ $d->id }}" class="w-full md:w-auto">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="bukaModalHapus('form-delete-dewan-{{ $d->id }}')" class="w-full md:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-red-600 transition-colors">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs font-bold text-navy/40 uppercase text-center p-4">Tidak ada data Dewan.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- BLOK B: PENGURUS INTI -->
                    <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                        <h3 class="font-black text-navy uppercase text-lg border-b border-gray-200 pb-2 mb-4">2. Pengurus Harian (Inti)</h3>
                        <div class="space-y-3">
                            @forelse($inti as $i)
                                <div class="flex flex-col md:flex-row gap-2 items-center bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                    <form action="{{ route('pengurus.update', $i->id) }}" method="POST" class="flex flex-col md:flex-row gap-2 flex-1 w-full">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="kategori" value="Inti">
                                        <input type="text" name="jabatan" value="{{ $i->jabatan }}" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                        <input type="text" name="nama" value="{{ $i->nama }}" class="w-full md:w-2/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-yellow hover:text-navy transition-colors">Simpan</button>
                                    </form>
                                    <form action="{{ route('pengurus.destroy', $i->id) }}" method="POST" id="form-delete-inti-{{ $i->id }}" class="w-full md:w-auto">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="bukaModalHapus('form-delete-inti-{{ $i->id }}')" class="w-full md:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-red-600 transition-colors">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs font-bold text-navy/40 uppercase text-center p-4">Tidak ada data Pengurus Inti.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- BLOK C: KOMISI & BIDANG -->
                    <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                        <h3 class="font-black text-navy uppercase text-lg border-b border-gray-200 pb-2 mb-4">3. Komisi & Bidang-Bidang</h3>
                        <div class="space-y-3">
                            @forelse($bidang as $b)
                                <div class="flex flex-col md:flex-row gap-2 items-center bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                    <form action="{{ route('pengurus.update', $b->id) }}" method="POST" class="flex flex-col md:flex-row gap-2 flex-1 w-full">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="kategori" value="Bidang">
                                        <input type="text" name="keterangan" value="{{ $b->keterangan }}" class="w-full md:w-1/4 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama Bidang" required>
                                        <select name="jabatan" class="w-full md:w-1/4 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                            <option value="Ketua" {{ $b->jabatan == 'Ketua' ? 'selected' : '' }}>Ketua Bidang</option>
                                            <option value="Wakil Ketua" {{ $b->jabatan == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua Bidang</option>
                                            <option value="Anggota" {{ $b->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                                        </select>
                                        <input type="text" name="nama" value="{{ $b->nama }}" class="w-full md:w-2/4 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-yellow hover:text-navy transition-colors">Simpan</button>
                                    </form>
                                    <form action="{{ route('pengurus.destroy', $b->id) }}" method="POST" id="form-delete-bidang-{{ $b->id }}" class="w-full md:w-auto">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="bukaModalHapus('form-delete-bidang-{{ $b->id }}')" class="w-full md:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-red-600 transition-colors">Hapus</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs font-bold text-navy/40 uppercase text-center p-4">Tidak ada data Bidang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ======================================================= -->
                <!-- MODE 2: KELOLA CABANG DAERAH                            -->
                <!-- ======================================================= -->
                <div id="form-cabang" class="space-y-8 hidden">

                    @php $cabangGrouped = $cabang->groupBy('nama_daerah'); @endphp

                    @forelse($cabangGrouped as $daerah => $pengurusCabang)
                        @php $infoDaerah = $pengurusCabang->first(); @endphp

                        <div class="bg-navy/5 border border-navy/10 p-6 md:p-8 rounded-[2rem] relative">
                            <span class="absolute -top-3 left-8 bg-yellow text-navy px-4 py-1 text-[10px] font-black uppercase rounded shadow-sm">Pengcab {{ $daerah }}</span>

                            <!-- FORM EDIT PROFIL WILAYAH & SK DAERAH -->
                            <form action="{{ route('pengurus.update', $infoDaerah->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-5 rounded-xl border border-gray-200 mt-2 mb-8 shadow-sm">
                                @csrf @method('PUT')
                                <input type="hidden" name="kategori" value="Cabang">
                                <input type="hidden" name="form_wilayah" value="1">

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase tracking-widest mb-1">Status Pengcab</label>
                                        <select name="status_cabang" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:border-yellow">
                                            <option value="Aktif" {{ $infoDaerah->status_cabang == 'Aktif' ? 'selected' : '' }}>Aktif Kepengurusan</option>
                                            <option value="Vakum" {{ $infoDaerah->status_cabang == 'Vakum' ? 'selected' : '' }}>Vakum / Reorganisasi</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[9px] font-black text-navy/60 uppercase tracking-widest mb-1">Alamat Sekretariat</label>
                                        <input type="text" name="keterangan" value="{{ $infoDaerah->keterangan }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-navy focus:border-yellow" placeholder="Alamat lengkap...">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-black text-navy/60 uppercase tracking-widest mb-1">Revisi SK (Opsional)</label>
                                        <input type="file" name="file_sk" accept=".pdf" class="w-full text-[10px] text-navy file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-navy file:text-yellow border border-gray-300 rounded p-0.5 bg-gray-50">
                                    </div>
                                    <div class="md:col-span-4 flex justify-end mt-2 pt-4 border-t border-gray-100">
                                        <button type="submit" class="bg-navy text-yellow px-5 py-2.5 rounded-lg font-black text-[10px] uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-sm">Simpan Profil & Dokumen Wilayah</button>
                                    </div>
                                </div>
                            </form>

                            <h4 class="text-xs font-black text-navy uppercase tracking-widest mb-3 pl-2">Susunan Anggota Individu:</h4>
                            <div class="space-y-3">
                                @foreach($pengurusCabang as $p)
                                    <div class="flex flex-col md:flex-row gap-2 items-center bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
                                        <form action="{{ route('pengurus.update', $p->id) }}" method="POST" class="flex flex-col md:flex-row gap-2 flex-1 w-full">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="kategori" value="Cabang">
                                            <input type="text" name="jabatan" value="{{ $p->jabatan }}" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                            <input type="text" name="nama" value="{{ $p->nama }}" class="w-full md:w-2/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                                            <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-yellow hover:text-navy transition-colors">Simpan</button>
                                        </form>
                                        <form action="{{ route('pengurus.destroy', $p->id) }}" method="POST" id="form-delete-cabang-{{ $p->id }}" class="w-full md:w-auto">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="bukaModalHapus('form-delete-cabang-{{ $p->id }}')" class="w-full md:w-auto bg-red-500 text-white px-4 py-2 rounded-lg font-black text-xs uppercase hover:bg-red-600 transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="bg-white/50 border border-gray-200 p-8 rounded-2xl text-center">
                            <p class="text-xs font-bold text-navy/40 uppercase tracking-widest">Belum ada data Pengurus Cabang Daerah terdaftar.</p>
                        </div>
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
                <h3 class="font-black text-navy text-xl uppercase mb-2">Hapus Pengurus?</h3>
                <p class="text-sm font-semibold text-navy/60 mb-8">
                    Tindakan ini bersifat permanen. Data pengurus ini akan dihapus dari struktur organisasi secara permanen.
                </p>
                <div class="flex flex-col md:flex-row w-full gap-3">
                    <button type="button" onclick="tutupModalHapus()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-navy font-bold py-3 px-4 rounded-xl transition-colors text-sm uppercase">
                        Batal
                    </button>
                    <button type="button" onclick="submitHapusPengurus()" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors text-sm uppercase">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>

    </section>

    <script>
        function toggleFormMode() {
            const tipe = document.querySelector('input[name="tipe_form"]:checked').value;
            const formProvinsi = document.getElementById('form-provinsi');
            const formCabang = document.getElementById('form-cabang');

            if (tipe === 'provinsi') {
                formProvinsi.classList.remove('hidden');
                formCabang.classList.add('hidden');
            } else {
                formProvinsi.classList.add('hidden');
                formCabang.classList.remove('hidden');
            }
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

        function submitHapusPengurus() {
            if (targetFormId) {
                document.getElementById(targetFormId).submit();
            }
        }
    </script>
@endsection
