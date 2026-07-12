@extends('layouts.main')

@section('title', 'Riwayat Pelatihan Saya - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 relative text-center">
        <div class="absolute top-6 left-6 md:top-12 md:left-16 z-20">
            <a href="{{ url('/pelatihan') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kelas
            </a>
        </div>

        <div class="mt-8 md:mt-0 relative z-10">
            <h1 class="font-oswald text-white text-3xl md:text-4xl font-bold uppercase tracking-wide">RIWAYAT PELATIHAN SAYA</h1>
            <p class="text-white/70 font-semibold mt-2 text-sm max-w-xl mx-auto">Pantau status pendaftaran, perbaiki berkas yang ditolak, dan unduh tiket masuk Anda di sini.</p>
        </div>
    </section>

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 md:p-8 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-navy text-white text-xs uppercase tracking-wider">
                                <th class="px-4 py-4 rounded-l-lg w-[30%]">Program Pelatihan</th>
                                <th class="px-4 py-4 w-[20%]">Tanggal & Tempat</th>
                                <th class="px-4 py-4 w-[20%]">Golongan Biaya</th>
                                <th class="px-4 py-4 text-center w-[15%]">Status</th>
                                <th class="px-4 py-4 rounded-r-lg text-center w-[15%]">Aksi / Akses</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-navy divide-y divide-gray-100">
                            @forelse($registrations as $r)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-5 align-top">
                                        <p class="font-black uppercase text-sm mb-1 text-navy">{{ $r->pelatihan->judul }}</p>
                                        <p class="text-[10px] text-navy/50 font-bold uppercase tracking-wider">Terdaftar: {{ $r->created_at->format('d/m/Y H:i') }}</p>
                                    </td>

                                    <td class="px-4 py-5 align-top">
                                        <p class="font-bold text-navy uppercase">{{ \Carbon\Carbon::parse($r->pelatihan->tanggal_pelaksanaan)->format('d F Y') }}</p>
                                        <p class="text-[10px] text-navy/60 font-medium">{{ $r->pelatihan->lokasi }}</p>
                                    </td>

                                    <td class="px-4 py-5 align-top">
                                        <p class="font-bold text-navy uppercase">{{ $r->golongan_biaya }}</p>
                                    </td>

                                    <td class="px-4 py-5 text-center align-top">
                                        <span class="px-3 py-1.5 rounded text-[10px] font-black uppercase tracking-wider inline-block
                                            {{ $r->status == 'Menunggu' ? 'bg-yellow/20 text-yellow-700 border border-yellow/20' : ($r->status == 'Diterima' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $r->status }}
                                        </span>
                                        @if($r->status == 'Ditolak' && $r->alasan_ditolak)
                                            <p class="text-[9px] text-red-500 mt-2 font-bold leading-tight" title="{{ $r->alasan_ditolak }}">
                                                "{{ Str::limit($r->alasan_ditolak, 40) }}"
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-4 py-5 text-center align-top">
                                        <div class="flex flex-col items-center gap-2">
                                            @if($r->status == 'Diterima')
                                                <a href="{{ route('pendaftaran.tiket', $r->pelatihan->id) }}" class="w-full text-center bg-navy text-yellow px-3 py-2 rounded font-black uppercase text-[10px] hover:bg-yellow hover:text-navy transition-colors shadow-sm cursor-pointer border border-navy/20">
                                                    Tiket QR
                                                </a>
                                                @if($r->pelatihan->link_wa_grup)
                                                    <a href="{{ $r->pelatihan->link_wa_grup }}" target="_blank" class="w-full text-center bg-green-600 text-white px-3 py-2 rounded font-black uppercase text-[10px] hover:bg-green-700 transition-colors shadow-sm border border-green-600/20">
                                                        Grup WA
                                                    </a>
                                                @endif

                                            @elseif($r->status == 'Ditolak')
                                                <a href="{{ url('/pelatihan/' . $r->pelatihan->id) }}" class="w-full text-center bg-white text-red-600 border border-red-200 hover:bg-red-600 hover:text-white px-3 py-2 rounded font-black uppercase text-[10px] transition-colors shadow-sm">
                                                    Perbaiki Berkas
                                                </a>

                                            @else
                                                <span class="text-navy/40 text-[10px] italic font-bold">Sedang Ditinjau Admin</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-navy/40 font-bold uppercase tracking-wide text-xs">
                                        Belum ada rekam jejak riwayat pendaftaran pelatihan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
@endsection
