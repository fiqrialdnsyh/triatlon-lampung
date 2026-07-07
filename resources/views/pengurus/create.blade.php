@extends('layouts.main')

@section('title', 'Input Pengurus - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <a href="{{ url('/pengurus') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE STRUKTUR PENGURUS
            </a>

            <div class="bg-cream p-6 md:p-10 rounded-[2rem] shadow-2xl">
                <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">INPUT KEPENGURUSAN BARU</h2>
                <p class="text-sm font-semibold text-navy/70 mb-8 pb-6 border-b border-navy/10">Data yang sudah terdaftar akan ditampilkan di dalam kotak abu-abu sebagai informasi. Jika ingin mengubah atau menghapus data lama, silakan gunakan menu Kelola Data.</p>

                <form action="{{ route('pengurus.store') }}" method="POST" enctype="multipart/form-data" id="main-form-pengurus">
                    @csrf

                    <div class="flex flex-col md:flex-row gap-4 mb-8">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tipe_form" value="provinsi" class="peer hidden" checked onchange="toggleFormMode()">
                            <div class="text-center p-4 rounded-xl border-2 border-gray-300 bg-white text-navy/50 font-black uppercase tracking-wider peer-checked:border-yellow peer-checked:bg-yellow/10 peer-checked:text-navy transition-all">
                                Kepengurusan Provinsi (Pusat)
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="tipe_form" value="cabang" class="peer hidden" onchange="toggleFormMode()">
                            <div class="text-center p-4 rounded-xl border-2 border-gray-300 bg-white text-navy/50 font-black uppercase tracking-wider peer-checked:border-yellow peer-checked:bg-yellow/10 peer-checked:text-navy transition-all">
                                Kepengurusan Cabang (Daerah)
                            </div>
                        </label>
                    </div>

                    <div id="form-provinsi" class="space-y-10">

                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row items-center gap-6">
                            <div class="flex-1">
                                <h3 class="font-black text-navy uppercase text-sm mb-1">Dokumen SK Provinsi</h3>
                                <p class="text-[10px] font-bold text-navy/50">SK akan otomatis terlampir ke nama pengurus yang baru Anda input di bawah.</p>
                            </div>
                            <input type="file" name="file_sk_provinsi" accept=".pdf" class="text-sm text-navy file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer border border-gray-300 rounded-lg p-1 bg-gray-50">
                        </div>

                        <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                                <div>
                                    <h3 class="font-black text-navy uppercase text-lg">1. Dewan Pembina & Penasihat</h3>
                                    <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Pilih posisi dewan dan masukkan nama lengkap atau instansi (Misal: Gubernur Provinsi Lampung).</p>
                                </div>
                            </div>

                            @if($dewanExist->count() > 0)
                                <div class="mb-4 bg-gray-100 p-4 rounded-xl border border-gray-200">
                                    <p class="text-[9px] font-black text-navy/50 uppercase tracking-widest mb-2">Data Terdaftar Saat Ini (Edit via Kelola Data):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($dewanExist as $d)
                                            <span class="bg-white border border-gray-300 text-[10px] font-bold text-navy px-2.5 py-1 rounded">{{ $d->jabatan !== '-' ? $d->jabatan : '' }} {{ $d->nama !== '-' ? $d->nama : '' }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div id="container-dewan" class="space-y-3"></div>
                            <button type="button" onclick="tambahBaris('dewan')" class="mt-4 text-xs font-black text-navy uppercase hover:text-yellow bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition-colors">
                                + Tambah Dewan Baru
                            </button>
                        </div>

                        <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                                <div>
                                    <h3 class="font-black text-navy uppercase text-lg">2. Pengurus Harian (Inti)</h3>
                                    <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Masukkan posisi struktural utama seperti Ketua Umum, Wakil Ketua, dll.</p>
                                </div>
                            </div>

                            @if($intiExist->count() > 0)
                                <div class="mb-4 bg-gray-100 p-4 rounded-xl border border-gray-200">
                                    <p class="text-[9px] font-black text-navy/50 uppercase tracking-widest mb-2">Data Terdaftar Saat Ini (Edit via Kelola Data):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($intiExist as $i)
                                            <span class="bg-white border border-gray-300 text-[10px] font-bold text-navy px-2.5 py-1 rounded"><span class="text-navy/50 mr-1">{{ $i->jabatan }}:</span> {{ $i->nama }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div id="container-inti" class="space-y-3"></div>
                            <button type="button" onclick="tambahBaris('inti')" class="mt-4 text-xs font-black text-navy uppercase hover:text-yellow bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition-colors">
                                + Tambah Pengurus Inti Baru
                            </button>
                        </div>

                        <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                            <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                                <div>
                                    <h3 class="font-black text-navy uppercase text-lg">3. Komisi & Bidang-Bidang</h3>
                                    <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Kelompokkan dengan mengisi 'Nama Bidang' yang sama, lalu pilih posisinya.</p>
                                </div>
                            </div>

                            @if($bidangExist->count() > 0)
                                <div class="mb-4 bg-gray-100 p-4 rounded-xl border border-gray-200">
                                    <p class="text-[9px] font-black text-navy/50 uppercase tracking-widest mb-2">Data Terdaftar Saat Ini (Edit via Kelola Data):</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($bidangExist as $b)
                                            <span class="bg-white border border-gray-300 text-[10px] font-bold text-navy px-2.5 py-1 rounded"><span class="text-navy/50 mr-1">[{{ $b->keterangan }}]</span> {{ $b->jabatan }} - {{ $b->nama }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div id="container-bidang" class="space-y-3"></div>
                            <button type="button" onclick="tambahBaris('bidang')" class="mt-4 text-xs font-black text-navy uppercase hover:text-yellow bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition-colors">
                                + Tambah Ketua/Anggota Bidang Baru
                            </button>
                        </div>
                    </div>

                    <div id="form-cabang" class="space-y-6 hidden">
                        <div class="bg-navy/5 border border-navy/10 p-6 rounded-2xl relative">
                            <span class="absolute -top-3 left-6 bg-yellow text-navy px-3 py-0.5 text-[9px] font-black uppercase rounded shadow-sm">Profil Pengurus Cabang</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">

                                <div>
                                    <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1">Nama Daerah <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="nama_daerah" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow appearance-none">
                                            @if(empty($daerahTersedia))
                                                <option value="" disabled selected>Seluruh 15 Daerah Sudah Terisi!</option>
                                            @else
                                                <option value="" disabled selected>-- Pilih Kabupaten/Kota --</option>
                                                @foreach($daerahTersedia as $d)
                                                    <option value="{{ $d }}">{{ $d }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                    @if(empty($daerahTersedia))
                                        <p class="text-[10px] font-bold text-red-500 mt-1">Harap gunakan menu Kelola Data untuk mengedit daerah.</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1">Status Keaktifan</label>
                                    <select name="status_cabang" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow">
                                        <option value="Aktif">Aktif Kepengurusan</option>
                                        <option value="Vakum">Vakum / Reorganisasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1">Alamat Sekretariat</label>
                                    <input type="text" name="keterangan" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:border-yellow" placeholder="Alamat lengkap...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-navy uppercase tracking-wide mb-1">Upload SK Daerah (PDF)</label>
                                    <input type="file" name="file_sk_cabang" accept=".pdf" class="w-full text-xs text-navy file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-navy file:text-yellow hover:file:bg-navy/90 cursor-pointer bg-white border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/50 border border-gray-200 p-6 rounded-2xl">
                            <h3 class="font-black text-navy uppercase text-lg border-b border-gray-200 pb-2 mb-4">Susunan Pengurus Daerah</h3>
                            <div id="container-baris-cabang" class="space-y-3"></div>
                            <button type="button" onclick="tambahCabang()" class="mt-4 text-xs font-black text-navy uppercase hover:text-yellow bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition-colors">
                                + Tambah Anggota Pengurus Daerah
                            </button>
                        </div>
                    </div>

                    <div class="pt-10 mt-8 border-t border-navy/10 flex justify-end">
                        <button type="submit" class="w-full md:w-auto bg-navy text-yellow px-12 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-xl">
                            SIMPAN SELURUH DATA BARU
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script>
        // Logika Utama Mengatasi Browser Validation Trap (Toggling Disabled Attribute)
        function toggleFormMode() {
            const tipe = document.querySelector('input[name="tipe_form"]:checked').value;
            const formProvinsi = document.getElementById('form-provinsi');
            const formCabang = document.getElementById('form-cabang');

            if (tipe === 'provinsi') {
                formProvinsi.classList.remove('hidden');
                formCabang.classList.add('hidden');

                // Aktifkan input Provinsi, Matikan input Cabang agar tidak di-validasi browser
                formProvinsi.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute('disabled'));
                formCabang.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute('disabled', 'true'));
            } else {
                formProvinsi.classList.add('hidden');
                formCabang.classList.remove('hidden');

                // Matikan input Provinsi, Aktifkan input Cabang agar tidak di-validasi browser
                formProvinsi.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute('disabled', 'true'));
                formCabang.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute('disabled'));
            }
        }

        function tambahBaris(tipe) {
            const container = document.getElementById(`container-${tipe}`);
            const newRow = document.createElement('div');
            newRow.className = "flex flex-col md:flex-row gap-3 items-center bg-white p-3 rounded-xl border border-gray-200";

            let innerHTML = '';

            if (tipe === 'dewan') {
                innerHTML = `
                    <input type="hidden" name="prov_kategori[]" value="Dewan">
                    <input type="hidden" name="prov_grup[]" value="">
                    <select name="prov_jabatan[]" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                        <option value="" disabled selected>Pilih Keterangan Dewan</option>
                        <option value="Pelindung">Pelindung</option>
                        <option value="Dewan Kehormatan">Dewan Kehormatan</option>
                        <option value="Dewan Pembina">Dewan Pembina</option>
                        <option value="Dewan Penasihat">Dewan Penasihat</option>
                    </select>
                    <input type="text" name="prov_nama[]" class="w-full md:w-2/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama / Instansi (Misal: Gubernur Provinsi Lampung)" required>
                    <button type="button" onclick="hapusElemen(this)" class="text-red-500 font-bold hover:text-red-700 px-3">X</button>
                `;
            } else if (tipe === 'inti') {
                innerHTML = `
                    <input type="hidden" name="prov_kategori[]" value="Inti">
                    <input type="hidden" name="prov_grup[]" value="">
                    <input type="text" name="prov_jabatan[]" class="w-full md:w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Jabatan (Misal: Ketua Umum)" required>
                    <input type="text" name="prov_nama[]" class="w-full md:w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama Lengkap & Gelar" required>
                    <button type="button" onclick="hapusElemen(this)" class="text-red-500 font-bold hover:text-red-700 px-3">X</button>
                `;
            } else if (tipe === 'bidang') {
                innerHTML = `
                    <input type="hidden" name="prov_kategori[]" value="Bidang">
                    <input type="text" name="prov_grup[]" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama Bidang (Misal: Binpres)" required>
                    <select name="prov_jabatan[]" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" required>
                        <option value="" disabled selected>Pilih Posisi</option>
                        <option value="Ketua">Ketua Bidang</option>
                        <option value="Wakil Ketua">Wakil Ketua Bidang</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                    <input type="text" name="prov_nama[]" class="w-full md:w-1/3 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama Lengkap" required>
                    <button type="button" onclick="hapusElemen(this)" class="text-red-500 font-bold hover:text-red-700 px-3">X</button>
                `;
            }

            newRow.innerHTML = innerHTML;
            container.appendChild(newRow);

            // Evaluasi ulang attribute disabled saat baris baru ditambahkan
            const tipeFormAktif = document.querySelector('input[name="tipe_form"]:checked').value;
            if(tipeFormAktif !== 'provinsi') {
                newRow.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute('disabled', 'true'));
            }
        }

        function tambahCabang() {
            const container = document.getElementById('container-baris-cabang');
            const newRow = document.createElement('div');
            newRow.className = "flex flex-col md:flex-row gap-3 items-center bg-white p-3 rounded-xl border border-gray-200";
            newRow.innerHTML = `
                <input type="text" name="cabang_jabatan[]" class="w-full md:w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Jabatan (Misal: Ketua Pengcab)" required>
                <input type="text" name="cabang_nama[]" class="w-full md:w-1/2 bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-navy focus:border-yellow" placeholder="Nama Lengkap" required>
                <button type="button" onclick="hapusElemen(this)" class="text-red-500 font-bold hover:text-red-700 px-3">X</button>
            `;
            container.appendChild(newRow);

            // Evaluasi ulang attribute disabled saat baris baru ditambahkan
            const tipeFormAktif = document.querySelector('input[name="tipe_form"]:checked').value;
            if(tipeFormAktif !== 'cabang') {
                newRow.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute('disabled', 'true'));
            }
        }

        function hapusElemen(button) {
            button.parentElement.remove();
        }

        document.addEventListener("DOMContentLoaded", function() {
            tambahBaris('dewan');
            tambahBaris('inti');
            tambahBaris('bidang');
            tambahCabang();

            // PENTING: Jalankan fungsi ini di awal agar form cabang yang defaultnya tersembunyi langsung ter-disabled
            toggleFormMode();
        });
    </script>
@endsection
