@extends('layouts.main')

@section('title', 'Edit Berita - FTI LAMPUNG')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }

        trix-toolbar {
            margin-bottom: 0.5rem;
        }

        trix-toolbar .trix-button-group {
            border-radius: 0.5rem;
            overflow: hidden;
            border-color: #d1d5db;
        }

        trix-toolbar [data-trix-button] {
            background-color: #ffffff;
            color: #0B1528;
        }

        trix-toolbar [data-trix-button]:hover {
            background-color: #f3f4f6;
        }

        trix-toolbar [data-trix-button].trix-active {
            background-color: #FFEB00;
            color: #0B1528;
            font-weight: bold;
        }

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

        trix-editor ul {
            list-style-type: disc !important;
            padding-left: 1.5rem !important;
            margin: 1rem 0 !important;
        }

        trix-editor ol {
            list-style-type: decimal !important;
            padding-left: 1.5rem !important;
            margin: 1rem 0 !important;
        }

        trix-editor li {
            margin-bottom: 0.5rem !important;
            display: list-item !important;
        }

        trix-editor p {
            margin-bottom: 1rem !important;
        }
    </style>

    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto flex flex-col xl:flex-row gap-8">

            <div class="xl:w-2/3 flex flex-col space-y-6">
                <a href="{{ route('berita.kelola') }}"
                    class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KEMBALI KE MANAJEMEN BERITA
                </a>

                <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl">
                    <h2 class="font-oswald text-3xl font-bold uppercase text-navy tracking-wide mb-2">EDIT ARTIKEL BERITA
                    </h2>
                    <p class="text-sm font-semibold text-navy/70 mb-8 border-b border-navy/10 pb-6">Ubah informasi teks
                        konten artikel di bawah.</p>

                    <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data"
                        id="form-edit-berita" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <textarea name="judul" id="judul-berita" rows="1"
                                class="w-full bg-transparent border-0 border-b-2 border-gray-400 px-0 py-3 text-2xl md:text-3xl font-black text-navy focus:outline-none focus:ring-0 focus:border-yellow transition-all placeholder-navy/30 resize-none overflow-hidden"
                                required>{{ $berita->judul }}</textarea>
                        </div>

                        <div class="pt-4">
                            <input id="konten_berita" type="hidden" name="konten" value="{{ $berita->konten }}" required>
                            <trix-editor input="konten_berita"></trix-editor>
                        </div>
                    </form>
                </div>
            </div>

            <div class="xl:w-1/3">
                <div class="bg-cream p-8 rounded-[2rem] shadow-xl sticky top-8">
                    <h3 class="font-black text-navy uppercase text-sm border-l-4 border-yellow pl-3 mb-6">Metadata Artikel
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Kategori
                                Berita <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select form="form-edit-berita" name="kategori"
                                    class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy focus:outline-none focus:ring-2 focus:ring-yellow appearance-none cursor-pointer"
                                    required>
                                    <option value="Kompetisi" {{ $berita->kategori == 'Kompetisi' ? 'selected' : '' }}>
                                        Kompetisi</option>
                                    <option value="Pengumuman" {{ $berita->kategori == 'Pengumuman' ? 'selected' : '' }}>
                                        Pengumuman</option>
                                    <option value="Program" {{ $berita->kategori == 'Program' ? 'selected' : '' }}>Program
                                        Kerja</option>
                                    <option value="Berita Umum" {{ $berita->kategori == 'Berita Umum' ? 'selected' : '' }}>
                                        Berita Umum</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-navy">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Nama
                                Penulis <span class="text-red-500">*</span></label>
                            <input form="form-edit-berita" type="text" name="penulis"
                                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy"
                                value="{{ $berita->penulis }}" required>
                        </div>

                        <div class="border-t border-navy/10 pt-6">
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-2">Foto Sampul
                                Utama</label>
                            <p class="text-[9px] font-semibold text-navy/50 mb-3">Klik gambar untuk mengganti dengan yang
                                baru.</p>

                            <div class="border-none rounded-xl bg-white/50 hover:border-yellow transition-all group cursor-pointer relative overflow-hidden h-48 flex items-center justify-center shadow-inner"
                                id="cover-upload-container">

                                <img id="image-preview" src="{{ asset($berita->foto_cover) }}"
                                    class="absolute inset-0 w-full h-full object-cover z-0 group-hover:opacity-40 transition-opacity">

                                <div
                                    class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                    <span
                                        class="bg-navy text-yellow text-xs font-bold px-4 py-2 rounded-lg uppercase shadow-xl flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Ganti Foto
                                    </span>
                                </div>

                                <input form="form-edit-berita" type="file" name="foto_cover" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" id="file-cover">
                            </div>
                        </div>

                        <div class="border-t border-navy/10 pt-6">
                            <label class="block text-[11px] font-bold text-navy/60 uppercase tracking-wide mb-1.5">Kata
                                Kunci (Tags)</label>
                            <input form="form-edit-berita" type="text" name="tags"
                                class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-semibold text-navy"
                                value="{{ $berita->tags }}" placeholder="Pisahkan dengan koma...">
                        </div>

                        <div class="pt-6">
                            <button form="form-edit-berita" type="submit"
                                class="w-full bg-navy text-yellow px-6 py-4 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-lg">
                                SIMPAN PERUBAHAN
                            </button>
                            <a href="{{ route('berita.kelola') }}"
                                class="block text-center mt-4 text-[11px] font-bold text-red-500 uppercase hover:text-red-700 transition-colors">Batal
                                Perubahan</a>
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
                }
                reader.readAsDataURL(file);
            }
        });

        const tx = document.getElementById('judul-berita');
        tx.setAttribute('style', 'height:' + (tx.scrollHeight) + 'px;overflow-y:hidden;');

        // Memanggil fungsi secara manual saat pertama kali load agar tinggi langsung sesuai
        tx.style.height = 0;
        tx.style.height = (tx.scrollHeight) + "px";

        tx.addEventListener("input", OnInput, false);

        function OnInput() {
            this.style.height = 0;
            this.style.height = (this.scrollHeight) + "px";
        }
    </script>
@endsection
