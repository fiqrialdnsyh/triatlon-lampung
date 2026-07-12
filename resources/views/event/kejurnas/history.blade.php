@extends('layouts.main')

@section('title', 'Histori Registrasi Kontingen - FTI LAMPUNG')

@section('content')
    <section class="bg-navy py-12 px-4 md:px-16 relative text-center">
        <div class="absolute top-6 left-6 md:top-12 md:left-16 z-20">
            <a href="{{ route('main_event.index') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Katalog
            </a>
        </div>

        <div class="mt-8 md:mt-0 relative z-10">
            <h1 class="font-oswald text-white text-3xl md:text-4xl font-bold uppercase tracking-wide">HISTORI DELEGASI KONTINGEN</h1>
            <p class="text-white/70 font-semibold mt-2 text-sm max-w-xl mx-auto">Daftar seluruh atlet yang telah Anda daftarkan beserta akses tiket masuk pertandingan.</p>
        </div>
    </section>

    <section class="bg-[#F8F9FA] py-16 px-4 md:px-16 min-h-screen">
        <div class="max-w-6xl mx-auto">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl shadow-sm">
                    <p class="text-xs font-black text-red-700 uppercase mb-2">Gagal Mengirim Perbaikan Berkas:</p>
                    <ul class="list-disc list-inside text-xs font-bold text-red-500/80 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-md p-6 md:p-8 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-navy text-white text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-l-lg">Nama Atlet & BIB</th>
                                <th class="px-4 py-3">Kejuaraan</th>
                                <th class="px-4 py-3">Kategori Lomba</th>
                                <th class="px-4 py-3 text-center">Status Verifikasi</th>
                                <th class="px-4 py-3 rounded-r-lg text-center">Aksi / Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-semibold text-navy divide-y divide-gray-100">
                            @forelse($registrations as $r)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <p class="font-black uppercase text-sm">{{ $r->nama_lengkap }}</p>
                                        <p class="text-[10px] text-navy/50 font-bold mt-0.5">BIB: {{ $r->bib_name }}</p>
                                    </td>
                                    <td class="px-4 py-4 font-black uppercase text-navy/80">{{ $r->event->judul }}</td>
                                    <td class="px-4 py-4">
                                        <p class="font-bold text-navy uppercase">{{ $r->kategori_lomba }}</p>
                                        <p class="text-[10px] text-navy/40">{{ $r->golongan_biaya }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-center align-top">
                                        <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-wider inline-block
                                            {{ $r->status_pembayaran == 'Menunggu' ? 'bg-yellow/20 text-yellow-700 border border-yellow/10' : ($r->status_pembayaran == 'Valid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $r->status_pembayaran }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                            @if($r->status_pembayaran == 'Valid')
                                                <button type="button" onclick="openTicketModal('{{ $r->qr_token }}', '{{ addslashes($r->event->judul) }}', '{{ addslashes($r->nama_lengkap) }}', '{{ addslashes($r->asal_daerah) }}', '{{ addslashes($r->kategori_lomba) }}')" class="w-full sm:w-auto text-center bg-navy text-yellow px-3 py-1.5 rounded font-black uppercase text-[9px] hover:bg-yellow hover:text-navy transition-colors shadow-sm cursor-pointer">
                                                    Lihat Tiket QR
                                                </button>
                                                @if($r->event->link_wa_grup)
                                                    <a href="{{ $r->event->link_wa_grup }}" target="_blank" class="w-full sm:w-auto text-center bg-green-600 text-white px-3 py-1.5 rounded font-black uppercase text-[9px] hover:bg-green-700 transition-colors shadow-sm">
                                                        Grup WhatsApp
                                                    </a>
                                                @endif

                                            @elseif($r->status_pembayaran == 'Ditolak')
                                                <div class="w-full sm:w-48 text-left bg-red-50 p-2.5 rounded-lg border border-red-100">
                                                    <p class="text-[9px] text-red-600 font-bold leading-snug line-clamp-2" title="{{ $r->pesan_penolakan }}">Alasan: {{ $r->pesan_penolakan }}</p>
                                                    <button type="button" onclick="openResubmitModal({{ $r->id }})" class="mt-2 w-full text-center bg-white text-red-600 hover:bg-red-600 hover:text-white border border-red-200 px-3 py-1.5 rounded font-black uppercase text-[9px] transition-colors shadow-sm cursor-pointer">
                                                        Perbaiki Berkas
                                                    </button>
                                                </div>

                                            @else
                                                <span class="text-navy/30 text-[10px] italic font-medium">Meninjau Pembayaran</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-navy/40 font-bold uppercase tracking-wide">Anda belum mendaftarkan atlet satupun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    @foreach($registrations->where('status_pembayaran', 'Ditolak') as $r)
        <div id="resubmitModal-{{ $r->id }}" class="fixed inset-0 z-[120] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeResubmitModal({{ $r->id }})"></div>

            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl relative z-10 flex flex-col overflow-hidden max-h-[90vh] animate-fadeIn">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 shrink-0">
                    <h3 class="font-black text-navy uppercase text-sm tracking-wider">Perbaikan Data Registrasi Atlet</h3>
                    <button onclick="closeResubmitModal({{ $r->id }})" class="text-red-500 font-black text-sm uppercase hover:underline cursor-pointer">Tutup</button>
                </div>

                <div class="p-6 flex-1 overflow-y-auto bg-gray-50">
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                        <h4 class="font-black text-red-700 uppercase text-[10px] tracking-wider mb-1">Catatan Penolakan Panitia:</h4>
                        <p class="text-xs font-bold text-red-600/80">{{ $r->pesan_penolakan }}</p>
                    </div>

                    <form action="{{ route('event.kejurnas.resubmit', $r->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ $r->nama_lengkap }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nomor Induk Kependudukan</label>
                                <input type="text" name="nomor_ktp" value="{{ $r->nomor_ktp }}" inputmode="numeric" pattern="[0-9]*" maxlength="16" minlength="16" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ $r->tempat_lahir }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ $r->tanggal_lahir }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                                    <option value="Putra" {{ $r->jenis_kelamin == 'Putra' ? 'selected' : '' }}>Putra</option>
                                    <option value="Putri" {{ $r->jenis_kelamin == 'Putri' ? 'selected' : '' }}>Putri</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Golongan Darah</label>
                                <select name="golongan_darah" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                                    <option value="A+" {{ $r->golongan_darah == 'A+' ? 'selected' : '' }}>A Positif (A+)</option>
                                    <option value="A-" {{ $r->golongan_darah == 'A-' ? 'selected' : '' }}>A Negatif (A-)</option>
                                    <option value="B+" {{ $r->golongan_darah == 'B+' ? 'selected' : '' }}>B Positif (B+)</option>
                                    <option value="B-" {{ $r->golongan_darah == 'B-' ? 'selected' : '' }}>B Negatif (B-)</option>
                                    <option value="AB+" {{ $r->golongan_darah == 'AB+' ? 'selected' : '' }}>AB Positif (AB+)</option>
                                    <option value="AB-" {{ $r->golongan_darah == 'AB-' ? 'selected' : '' }}>AB Negatif (AB-)</option>
                                    <option value="O+" {{ $r->golongan_darah == 'O+' ? 'selected' : '' }}>O Positif (O+)</option>
                                    <option value="O-" {{ $r->golongan_darah == 'O-' ? 'selected' : '' }}>O Negatif (O-)</option>
                                    <option value="Tidak Tahu" {{ $r->golongan_darah == 'Tidak Tahu' ? 'selected' : '' }}>Tidak Tahu</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Asal Daerah Domisili</label>
                                <input type="text" name="asal_daerah" value="{{ $r->asal_daerah }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>{{ $r->alamat }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Email Atlet</label>
                                <input type="email" name="email" value="{{ $r->email }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">No WhatsApp</label>
                                <input type="text" name="nomor_telepon" value="{{ $r->nomor_telepon }}" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-bold text-navy focus:outline-none" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-1">Nama BIB (Maks. 15 Huruf)</label>
                                <input type="text" name="bib_name" value="{{ $r->bib_name }}" maxlength="15" class="w-full bg-white border border-gray-300 rounded px-4 py-2.5 text-xs font-black text-navy uppercase focus:outline-none" required>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-2">Pilihan Golongan Tarif</label>
                            <div class="space-y-2 mb-4">
                                @php $skemas = json_decode($r->event->skema_biaya, true) ?? []; @endphp
                                @foreach($skemas as $s)
                                    <label class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-navy transition-colors">
                                        <input type="radio" name="golongan_biaya" value="{{ $s['nama'] }}" {{ $r->golongan_biaya == $s['nama'] ? 'checked' : '' }} class="w-4 h-4 text-navy focus:ring-navy cursor-pointer" required>
                                        <span class="text-xs font-bold text-navy">{{ $s['nama'] }} <span class="text-gray-400 ml-1"> (Rp {{ number_format($s['biaya'], 0, ',', '.') }})</span></span>
                                    </label>
                                @endforeach
                            </div>

                            <label class="block text-[10px] font-black text-navy uppercase tracking-widest mb-2 mt-4">Unggah Resi Transaksi Baru (Opsional)</label>
                            <p class="text-[9px] text-slate-500 font-bold mb-2">Kosongkan jika Anda tidak ingin mengubah gambar resi bukti pembayaran yang lama.</p>
                            <input type="file" name="bukti_transfer" accept="image/*" class="w-full text-xs text-navy file:mr-3 file:py-1.5 file:px-3 file:bg-navy file:text-yellow file:border-0 border border-gray-300 rounded p-0.5 bg-white cursor-pointer">
                        </div>

                        <button type="submit" class="w-full bg-navy text-yellow py-4 rounded-xl font-black text-xs uppercase tracking-wider transition-colors shadow-md mt-6 hover:bg-navy/90 cursor-pointer">
                            Simpan Perbaikan & Kirim Ulang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div id="ticketModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-navy/80 backdrop-blur-sm cursor-pointer" onclick="closeTicketModal()"></div>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let qrCodeInstance = null;

        function openTicketModal(token, eventName, athleteName, region, category) {
            document.getElementById('modalEventName').innerText = eventName;
            document.getElementById('modalAthleteName').innerText = athleteName;
            document.getElementById('modalRegion').innerText = region;
            document.getElementById('modalCategory').innerText = category;
            document.getElementById('modalToken').innerText = token;

            const qrBox = document.getElementById('qrcode-box');
            qrBox.innerHTML = '';

            qrCodeInstance = new QRCode(qrBox, {
                text: token,
                width: 192,
                height: 192,
                colorDark : "#0B1528",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            document.getElementById('ticketModal').classList.remove('hidden');
            document.getElementById('ticketModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketModal() {
            document.getElementById('ticketModal').classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }

        // FUNGSI UNTUK MODAL RESUBMIT
        function openResubmitModal(id) {
            document.getElementById('resubmitModal-' + id).classList.remove('hidden');
            document.getElementById('resubmitModal-' + id).classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeResubmitModal(id) {
            document.getElementById('resubmitModal-' + id).classList.replace('flex', 'hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
