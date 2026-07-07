@extends('layouts.main')

@section('title', 'Kelola Berita - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <a href="{{ url('/berita') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE HALAMAN BERITA
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b-2 border-navy/10 pb-6">
                    <div>
                        <h2 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy tracking-wide mb-1">MANAJEMEN KELOLA BERITA</h2>
                        <p class="text-sm font-bold text-navy/70 uppercase">Total Publikasi: {{ $beritas->count() }} Artikel</p>
                    </div>
                    <a href="{{ url('/berita/create') }}" class="mt-4 md:mt-0 bg-navy text-yellow px-5 py-2.5 font-black text-xs uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-md">
                        + TULIS BERITA BARU
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-navy text-white text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-tl-lg">No</th>
                                <th class="px-4 py-3">Cover</th>
                                <th class="px-4 py-3">Judul Berita</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tanggal Rilis</th>
                                <th class="px-4 py-3 rounded-tr-lg">Aksi Kontrol</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-semibold text-navy divide-y divide-gray-300">

                            @forelse ($beritas as $index => $item)
                                <tr class="hover:bg-white/50 transition-colors">
                                    <td class="px-4 py-4">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <img src="{{ asset($item->foto_cover) }}" class="w-20 h-12 object-cover rounded-md shadow-sm border border-gray-300">
                                    </td>
                                    <td class="px-4 py-4 font-black max-w-xs truncate" title="{{ $item->judul }}">
                                        {{ $item->judul }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="bg-navy/10 text-navy text-[10px] font-black uppercase px-2.5 py-1 rounded">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-xs">
                                        {{ $item->created_at->format('d/m/Y') }}<br>
                                        <span class="text-navy/50">{{ $item->created_at->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('berita.edit', $item->id) }}" class="text-xs bg-yellow text-navy px-3 py-1.5 font-bold uppercase rounded hover:bg-navy hover:text-yellow transition-colors">
                                                EDIT
                                            </a>

                                            <form action="{{ route('berita.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs bg-red-500 text-white px-3 py-1.5 font-bold uppercase rounded hover:bg-red-600 transition-colors">
                                                    HAPUS
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-navy/50 font-bold uppercase tracking-wider">Belum ada berita yang dapat dikelola.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection
