@extends('layouts.main')

@section('title', 'Tulis Berita Baru - FTI LAMPUNG')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        trix-toolbar [data-trix-button-group="file-tools"] { display: none; }
        trix-toolbar { margin-bottom: 0.5rem; }
        trix-toolbar .trix-button-group { border-radius: 0.5rem; overflow: hidden; border-color: #d1d5db; }
        trix-toolbar [data-trix-button] { background-color: #ffffff; color: #0B1528; }
        trix-toolbar [data-trix-button]:hover { background-color: #f3f4f6; }
        trix-toolbar [data-trix-button].trix-active { background-color: #FFEB00; color: #0B1528; font-weight: bold; }

        trix-editor {
            background-color: white;
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem;
            min-height: 400px;
            padding: 1.5rem !important;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0B1528;
            line-height: 2;
            transition: all 0.3s;
        }
        trix-editor:focus-within {
            outline: none !important;
            border-color: #FFEB00 !important;
            box-shadow: 0 0 0 2px rgba(255, 235, 0, 0.4) !important;
        }
        trix-editor blockquote {
            border-left: 4px solid #FFEB00 !important;
            padding-left: 1rem !important;
            margin-left: 0 !important;
            font-style: italic !important;
            color: #0B1528 !important;
            background-color: rgba(11, 21, 40, 0.05) !important;
            padding: 1rem !important;
            border-radius: 0 0.5rem 0.5rem 0 !important;
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }
    </style>

    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto flex flex-col xl:flex-row gap-8">

            <div class="xl:w-2/3 flex flex-col space-y-6">
                <a href="{{ url('/berita') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    KEMBALI KE DAFTAR BERITA
                </a>

                <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl">
                    <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">TULIS ARTIKEL BARU</h2>
                    <p class="text-sm font-semibold text-navy/70 mb-8 border-b border-navy/10 pb-6">Gunakan editor di bawah untuk menyusun berita. Anda dapat menebalkan teks, membuat daftar, hingga menyematkan kutipan.</p>

                    <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="form-berita" class="space-y-6">
                        @csrf

                        <div>
                            <textarea name="judul" id="judul-berita" rows="1" class="w-full bg-transparent border-0 border-b-2 border-gray-400 px-0 py-3 text-2xl md:text-3xl font-black text-navy focus:outline-none focus:ring-0 focus:border-yellow transition-all placeholder-navy/30 resize-none overflow-hidden" placeholder="Masukkan Judul Berita di sini..." required></textarea>
                        </div>

                        <div class="pt-4">
                            <input id="konten_berita" type="hidden" name="konten" required>
                            <trix-editor input="konten_berita" placeholder="Mulai menulis berita Anda yang luar biasa di sini..."></trix-editor>
                        </div>
                    </form>
                </div>
            </div>

            <div class="xl:w-1/3">
                <div class="bg-cream p-8 rounded-[2rem] shadow-xl sticky top-8">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3 mb-6">Pengaturan Publikasi</h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Kategori Berita <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select form="form-berita" name="kategori" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm appearance-none cursor-pointer" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Kompetisi">Kompetisi</option>
                                    <option value="Pengumuman">Pengumuman</option>
                                    <option value="Program">Program Kerja</option>
                                    <option value="Berita Umum">Berita Umum</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Nama Penulis <span class="text-red-500">*</span></label>
                            <input form="form-berita" type="text" name="penulis" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" value="Humas FOPI Lampung" required>
                        </div>

                        <div class="border-t border-navy/10 pt-6">
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-2">Foto Sampul Utama <span class="text-red-500">*</span></label>

                            <div class="border-2 border-dashed border-gray-400 rounded-xl bg-white/50 hover:bg-white hover:border-yellow transition-all group cursor-pointer relative overflow-hidden h-48 flex items-center justify-center" id="cover-upload-container">

                                <div id="upload-placeholder" class="text-center pointer-events-none relative z-10">
                                    <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-yellow mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs font-bold text-navy mb-1">Pilih Gambar</p>
                                    <p class="text-[9px] font-semibold text-navy/50 bg-white/60 px-2 py-0.5 rounded">JPG/PNG (Maks 2MB)</p>
                                </div>

                                <img id="image-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover z-0">

                                <input form="form-berita" type="file" name="foto_cover" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" id="file-cover" required>
                            </div>
                        </div>

                        <div class="border-t border-navy/10 pt-6">
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Kata Kunci (Tags)</label>
                            <input form="form-berita" type="text" name="tags" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow transition-all shadow-sm" placeholder="Pisahkan dengan koma...">
                        </div>

                        <div class="pt-6">
                            <button form="form-berita" type="submit" class="w-full bg-navy text-yellow px-6 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg flex items-center justify-center group">
                                <span>PUBLIKASIKAN</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <a href="{{ url('/berita') }}" class="block text-center mt-4 text-[11px] font-bold text-red-500 uppercase hover:text-red-700 transition-colors">Batal / Hapus Draf</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <script>
        document.getElementById('file-cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('cover-upload-container').classList.add('border-none');
                }
                reader.readAsDataURL(file);
            }
        });

        const tx = document.getElementById('judul-berita');
        tx.setAttribute('style', 'height:' + (tx.scrollHeight) + 'px;overflow-y:hidden;');
        tx.addEventListener("input", OnInput, false);

        function OnInput() {
            this.style.height = 0;
            this.style.height = (this.scrollHeight) + "px";
        }
    </script>
@endsection
