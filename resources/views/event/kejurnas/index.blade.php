@extends('layouts.main')

@section('title', 'Kejurnas Tournament - FTI LAMPUNG')

@section('content')
    <section class="bg-navy pt-24 pb-16 px-8 md:px-16 text-center relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto">
            <span class="text-yellow text-sm font-black tracking-widest uppercase mb-4 block">Pendaftaran Tertutup Kontingen</span>
            <h1 class="font-oswald text-white text-4xl md:text-5xl font-bold uppercase tracking-wide mb-4">KEJUARAAN NASIONAL</h1>
            <div class="w-20 h-1 bg-yellow mx-auto mb-6"></div>

            @auth
                @if(auth()->user()->role === 'kontingen')
                    <a href="{{ route('event.kejurnas.history') }}" class="inline-block bg-white text-navy px-6 py-3 rounded-lg font-black text-xs uppercase tracking-wider hover:bg-yellow transition-colors shadow-lg border border-navy">
                        Lihat Histori Registrasi Atlet
                    </a>
                @endif
            @endauth
        </div>
    </section>

    @auth
        @if(auth()->user()->isAdmin())
            <div class="bg-white border-b border-gray-200 py-8 px-4 md:px-16 relative shadow-sm">
                <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <h3 class="font-black text-navy uppercase text-xs tracking-wider border-b border-gray-200 pb-2 mb-4">Buat Akun Kontingen Baru</h3>
                        <form action="{{ route('event.kejurnas.buat_kontingen') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-bold text-navy uppercase mb-1">Nama Kontingen / Pengprov</label>
                                <input type="text" name="name" placeholder="Contoh: Kontingen DKI Jakarta" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs font-semibold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-navy uppercase mb-1">Email Akun</label>
                                <input type="email" name="email" placeholder="kontingen@mail.com" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs font-semibold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-navy uppercase mb-1">Password Akses</label>
                                <input type="password" name="password" class="w-full bg-white border border-gray-300 rounded px-3 py-2 text-xs font-semibold text-navy focus:outline-none" required>
                            </div>
                            <button type="submit" class="w-full bg-navy text-yellow py-2 text-xs font-black uppercase tracking-wider rounded transition-colors hover:bg-navy/90">Daftarkan Akun</button>
                        </form>
                    </div>

                    <div class="lg:col-span-2 bg-gray-50 p-6 rounded-xl border border-gray-200 flex flex-col justify-between">
                        <div>
                            <h3 class="font-black text-navy uppercase text-xs tracking-wider border-b border-gray-200 pb-2 mb-4">Daftar Akun Kontingen Aktif Sistem</h3>
                            <div class="overflow-y-auto max-h-44 border border-gray-200 rounded bg-white">
                                <table class="w-full text-left text-[11px] border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 text-navy uppercase font-black border-b border-gray-200">
                                            <th class="p-2">Nama Kontingen Resmi</th>
                                            <th class="p-2">Username / Email</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 font-semibold text-navy/80">
                                        @foreach($kontingens ?? [] as $k)
                                            <tr>
                                                <td class="p-2 uppercase font-bold text-navy">{{ $k->name }}</td>
                                                <td class="p-2 font-mono">{{ $k->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <a href="{{ route('event.kejurnas.create') }}" class="bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-lg hover:bg-navy hover:text-yellow transition-colors shadow-sm">
                                + BUAT MASTER EVENT KEJURNAS
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endauth

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            @if(session('success'))
                <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($events as $event)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden flex flex-col hover:-translate-y-1 transition-transform duration-300">
                        <div class="h-52 bg-gray-100 relative overflow-hidden">
                            @if($event->poster)
                                <img src="{{ asset($event->poster) }}" alt="{{ $event->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-navy/20 bg-navy/5">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-navy text-white px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-wider">
                                {{ $event->status }}
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-black text-navy text-lg uppercase leading-tight mb-4 line-clamp-2 min-h-[44px] flex items-center">{{ $event->judul }}</h3>
                                <div class="space-y-2 border-t border-gray-100 pt-4 mb-6 text-[11px] font-semibold text-navy/70">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $event->lokasi }}
                                    </div>
                                </div>
                            </div>

                            @if(auth()->check() && auth()->user()->isAdmin())
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <a href="{{ route('event.kejurnas.edit', $event->id) }}" class="block text-center bg-gray-100 text-navy py-2.5 rounded-lg font-black text-[10px] uppercase tracking-wider hover:bg-gray-200 transition-colors border border-gray-200">
                                        Edit Form
                                    </a>
                                    <a href="{{ route('event.kejurnas.show', $event->slug) }}" class="block text-center bg-navy text-yellow py-2.5 rounded-lg font-black text-[10px] uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors">
                                        Verifikasi
                                    </a>
                                </div>
                            @else
                                <a href="{{ route('event.kejurnas.show', $event->slug) }}" class="block w-full text-center bg-navy text-yellow py-3 rounded-xl font-black text-xs uppercase tracking-wider hover:bg-yellow hover:text-navy transition-colors shadow-sm mt-4">
                                    Masuk Panel Pendaftaran
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full border-2 border-dashed border-gray-300 p-12 rounded-2xl text-center bg-white">
                        <p class="font-black text-navy/40 uppercase text-sm tracking-wider">Belum ada agenda agenda kejuaraan nasional tertutup.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>
@endsection
