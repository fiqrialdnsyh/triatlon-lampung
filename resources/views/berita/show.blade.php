@extends('layouts.main')

@section('title', $berita->judul . ' - FTI LAMPUNG')

@section('content')
    <section class="relative w-full h-[400px] md:h-[500px]">
        <img src="{{ asset($berita->foto_cover) }}" alt="Cover Berita" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-navy/60 mix-blend-multiply"></div>

        <a href="{{ url('/berita') }}"
            class="absolute top-8 left-8 z-20 inline-flex items-center text-white/90 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors bg-navy/50 px-4 py-2 rounded-xl backdrop-blur-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            KEMBALI KE LIST BERITA
        </a>
    </section>

    <section class="bg-navy px-4 md:px-16 pb-24 relative z-10">
        <div class="max-w-4xl mx-auto bg-cream p-8 md:p-14 rounded-[2rem] shadow-2xl -mt-24">

            <div class="text-center mb-10 border-b-2 border-navy/10 pb-8">
                <div
                    class="inline-block bg-yellow text-navy text-[10px] font-black px-3 py-1.5 rounded-sm uppercase tracking-wider mb-4 shadow-sm">
                    {{ $berita->kategori }}
                </div>
                <h1 class="font-oswald text-3xl md:text-5xl font-bold uppercase text-navy leading-tight mb-4">
                    {{ $berita->judul }}</h1>
                <div
                    class="flex items-center justify-center space-x-4 text-xs font-bold text-navy/60 uppercase tracking-widest">
                    <span>Oleh: {{ $berita->penulis }}</span>
                    <span>•</span>
                    <span>{{ $berita->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            {{-- Tanda kurung kurawal ganda dengan tanda seru digunakan agar format HTML dari Trix Editor tereksekusi dengan benar --}}
            <article
                class="prose prose-navy max-w-none text-sm md:text-base font-semibold text-navy/80 leading-loose space-y-6 text-justify">
                {!! $berita->konten !!}
            </article>

            <div class="mt-12 pt-6 border-t border-navy/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap gap-2">
                    @if ($berita->tags)
                        @foreach (explode(',', $berita->tags) as $tag)
                            <span
                                class="text-[10px] font-black text-white bg-navy px-3 py-1.5 rounded-md uppercase">#{{ trim($tag) }}</span>
                        @endforeach
                    @endif
                </div>
                <button onclick="bagikanArtikel()"
                    class="inline-flex items-center text-xs font-black text-navy uppercase border-2 border-navy hover:bg-navy hover:text-white px-6 py-2 rounded-xl transition-colors group">
                    <svg class="w-4 h-4 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                        </path>
                    </svg>
                    BAGIKAN ARTIKEL
                </button>
            </div>

        </div>
    </section>

    <!-- CSS Tambahan agar konten Trix Editor (seperti daftar list atau kutipan) memiliki desain yang rapi saat ditampilkan -->
    <style>
        .prose blockquote {
            border-left: 4px solid #FFEB00;
            padding-left: 1rem;
            font-style: italic;
            background-color: rgba(11, 21, 40, 0.03);
            padding: 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin: 2rem 0;
            color: #0B1528;
            font-weight: 700;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose strong {
            color: #0B1528;
            font-weight: 900;
        }

        .prose a {
            color: #2563eb;
            text-decoration: underline;
        }

        .prose p {
            text-align: justify;
        }
    </style>

    <script>
        function bagikanArtikel() {
            // Cek apakah browser mendukung Web Share API (sebagian besar HP & Browser modern mendukung ini)
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($berita->judul) }}',
                    text: 'Baca berita terbaru dari TRIATLON LAMPUNG: {{ addslashes($berita->judul) }}',
                    url: window.location.href
                }).catch((error) => console.log('Gagal membagikan', error));
            } else {
                // Jika tidak didukung, otomatis salin tautan ke clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Tautan artikel berhasil disalin ke clipboard!');
                }, function(err) {
                    alert('Gagal menyalin tautan.');
                });
            }
        }
    </script>
@endsection
