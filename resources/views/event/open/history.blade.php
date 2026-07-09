@extends('layouts.main')

@section('title', 'Riwayat Kejuaraan Saya - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 relative text-center">
        <!-- TOMBOL KEMBALI -->
        <div class="absolute top-6 left-6 md:top-12 md:left-16 z-20">
            <a href="{{ route('event.open.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="mt-8 md:mt-0 relative z-10">
            <h1 class="font-oswald text-white text-3xl md:text-4xl font-bold uppercase tracking-wide">RIWAYAT EVENT SAYA</h1>
        </div>
    </section>

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 md:p-8 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-navy text-white text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-l-lg">Kejuaraan</th>
                                <th class="px-4 py-3">Kategori Lomba</th>
                                <th class="px-4 py-3">Tarif Golongan</th>
                                <th class="px-4 py-3 text-center">Status Verifikasi</th>
                                <th class="px-4 py-3 rounded-r-lg text-center">Aksi / Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-navy divide-y divide-gray-100">
                            @forelse($registrations as $r)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 font-black uppercase">{{ $r->event->judul }}</td>
                                    <td class="px-4 py-4 uppercase text-navy/70">{{ $r->kategori_lomba }}</td>
                                    <td class="px-4 py-4">
                                        <p class="font-bold text-navy">{{ $r->golongan_biaya }}</p>
                                        <p class="text-[10px] text-navy/40">Rp {{ number_format($r->nominal_bayar, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-wider
                                            {{ $r->status_pembayaran == 'Menunggu' ? 'bg-yellow/20 text-yellow-700 border border-yellow/10' : ($r->status_pembayaran == 'Valid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $r->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                            @if($r->status_pembayaran == 'Valid')
                                                <!-- TOMBOL PEMICU POPUP MODAL TIKET -->
                                                <button type="button" onclick="openTicketModal('{{ $r->qr_token }}', '{{ addslashes($r->event->judul) }}', '{{ addslashes($r->nama_lengkap) }}', '{{ addslashes($r->asal_daerah) }}', '{{ addslashes($r->kategori_lomba) }}')" class="w-full sm:w-auto text-center bg-navy text-yellow px-3 py-1.5 rounded font-black uppercase text-[9px] hover:bg-yellow hover:text-navy transition-colors shadow-sm cursor-pointer">
                                                    Lihat Tiket QR
                                                </button>

                                                @if($r->event->link_wa_grup)
                                                    <a href="{{ $r->event->link_wa_grup }}" target="_blank" class="w-full sm:w-auto text-center bg-green-600 text-white px-3 py-1.5 rounded font-black uppercase text-[9px] hover:bg-green-700 transition-colors shadow-sm">
                                                        Grup WhatsApp
                                                    </a>
                                                @endif
                                            @elseif($r->status_pembayaran == 'Ditolak')
                                                <span class="text-red-500 text-[10px] italic font-bold">Berkas Tidak Valid</span>
                                            @else
                                                <span class="text-navy/30 text-[10px] italic font-medium">Meninjau Pembayaran</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-navy/40 font-bold uppercase tracking-wide">Tidak ditemukan rekam jejak riwayat pendaftaran event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <!-- MODAL TIKET QR CHECK-IN -->
    <div id="ticketModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeTicketModal()"></div>

        <!-- KARTU PAS MASUK TEKNIKAL MEETING -->
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl border border-gray-200 relative z-10 flex flex-col overflow-hidden animate-fadeIn">

            <div class="bg-[#0B1528] w-full py-4 text-center border-b-4 border-yellow-400 relative">
                <button onclick="closeTicketModal()" class="absolute top-2 right-4 text-white/50 hover:text-white font-black text-sm cursor-pointer">X</button>
                <p class="text-yellow-400 text-[10px] font-black tracking-widest uppercase">Federasi Triathlon Indonesia</p>
                <h2 class="text-white text-xs font-black uppercase mt-0.5 tracking-wider">Provinsi Lampung</h2>
            </div>

            <div class="p-6 w-full flex-1 flex flex-col justify-between items-center space-y-5">
                <div class="text-center w-full">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Kejuaraan</p>
                    <h1 id="modalEventName" class="text-base font-black uppercase text-navy leading-tight line-clamp-2">...</h1>
                </div>

                <!-- KONTAINER QR CODE (Digenerate oleh JS) -->
                <div class="p-3 bg-white border-2 border-navy rounded-xl shadow-sm">
                    <div id="qrcode-box" class="w-48 h-48 flex items-center justify-center"></div>
                </div>

                <div class="w-full border-t border-dashed border-gray-200 pt-4 grid grid-cols-2 gap-3 text-left text-xs">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nama Atlet</p>
                        <p id="modalAthleteName" class="font-black uppercase mt-0.5 text-sm text-navy">...</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Daerah / Klub</p>
                        <p id="modalRegion" class="font-black uppercase mt-0.5 text-sm text-navy">...</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Kategori Lomba</p>
                        <p id="modalCategory" class="font-black uppercase mt-0.5 text-xs text-blue-800">...</p>
                    </div>
                    <div class="col-span-2 border-t border-gray-100 pt-3 text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kode Verifikasi Unik</p>
                        <p id="modalToken" class="font-mono font-black text-sm text-slate-700 tracking-widest mt-0.5">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 w-full py-3 text-center border-t border-gray-100 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
                Tunjukkan kode QR ini ke meja panitia
            </div>
        </div>
    </div>

    <!-- LIBRARY QR CODE GENERATOR & SCRIPT LOGIKA -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let qrCodeInstance = null;

        function openTicketModal(token, eventName, athleteName, region, category) {
            // Memasukkan data ke dalam modal
            document.getElementById('modalEventName').innerText = eventName;
            document.getElementById('modalAthleteName').innerText = athleteName;
            document.getElementById('modalRegion').innerText = region;
            document.getElementById('modalCategory').innerText = category;
            document.getElementById('modalToken').innerText = token;

            // Bersihkan QR Code lama jika ada
            const qrBox = document.getElementById('qrcode-box');
            qrBox.innerHTML = '';

            // Generasi QR Code baru langsung di peramban
            qrCodeInstance = new QRCode(qrBox, {
                text: token,
                width: 192,
                height: 192,
                colorDark : "#0B1528",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            // Tampilkan Modal
            document.getElementById('ticketModal').classList.remove('hidden');
            document.getElementById('ticketModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketModal() {
            document.getElementById('ticketModal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
