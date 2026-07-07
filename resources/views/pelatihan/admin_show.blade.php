@extends('layouts.main')

@section('title', 'Kelola Pendaftar - ' . $pelatihan->judul)

@section('content')
    <style>
        @media print {
            header, footer, .no-print { display: none !important; }
            body { background-color: white !important; }
            .print-area { box-shadow: none !important; padding: 0 !important; background-color: white !important; }
            .print-table { border-collapse: collapse; width: 100%; }
            .print-table th, .print-table td { border: 1px solid #000; padding: 8px; color: black; }
        }
    </style>

    <section class="bg-navy py-12 px-8 md:px-16 min-h-screen no-print relative">
        <div class="max-w-6xl mx-auto">

            <a href="{{ url('/pelatihan') }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors mb-6 no-print">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                KEMBALI KE DAFTAR PELATIHAN
            </a>

            <div class="bg-cream p-8 md:p-10 rounded-[2rem] shadow-2xl print-area">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-bold text-sm no-print">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b-2 border-navy/10 pb-6">
                    <div>
                        <h2 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy tracking-wide mb-1">DATA PESERTA TERDAFTAR</h2>
                        <p class="text-sm font-bold text-navy/70 uppercase">{{ $pelatihan->judul }}</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-3 no-print">
                        <button onclick="window.print()" class="inline-flex items-center bg-navy text-white px-5 py-2.5 font-black text-xs uppercase rounded-xl hover:bg-yellow hover:text-navy transition-colors shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            CETAK PDF
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 bg-white/50 p-4 rounded-xl border border-gray-300">
                    <div>
                        <p class="text-[10px] font-bold text-navy/60 uppercase">Tanggal Pelaksanaan</p>
                        <p class="text-sm font-black text-navy">{{ \Carbon\Carbon::parse($pelatihan->tanggal_pelaksanaan)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-navy/60 uppercase">Batas Pendaftaran</p>
                        <p class="text-sm font-black text-navy">{{ \Carbon\Carbon::parse($pelatihan->batas_pendaftaran)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-navy/60 uppercase">Status Sistem</p>
                        <p class="text-sm font-black text-navy">{{ $pelatihan->status }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-navy/60 uppercase">Total Peserta</p>
                        <p class="text-sm font-black text-navy">{{ $pelatihan->pendaftarans->count() }} Orang</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left print-table">
                        <thead>
                            <tr class="bg-navy text-white text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-tl-lg print:rounded-none">No</th>
                                <th class="px-4 py-3">Nama & Kontak</th>
                                <th class="px-4 py-3">Asal Daerah</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Waktu Daftar</th>
                                <th class="px-4 py-3 rounded-tr-lg print:rounded-none no-print">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-semibold text-navy divide-y divide-gray-300">

                            @forelse ($pelatihan->pendaftarans as $index => $pendaftar)
                                <tr class="hover:bg-white/50 transition-colors {{ $pendaftar->status == 'Ditolak' ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-4">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <span class="block font-black">{{ $pendaftar->nama_lengkap }}</span>
                                        <span class="text-[10px] text-navy/70">{{ $pendaftar->pekerjaan }} ({{ $pendaftar->usia }}th)</span>
                                    </td>
                                    <td class="px-4 py-4">{{ $pendaftar->asal_daerah }}</td>
                                    <td class="px-4 py-4">
                                        <span class="block">{{ $pendaftar->golongan_biaya }}</span>
                                        @if($pendaftar->status === 'Menunggu')
                                            <span class="text-xs font-bold text-yellow-600">MENUNGGU VALIDASI</span>
                                        @elseif($pendaftar->status === 'Diterima')
                                            <span class="text-xs font-bold text-green-600">DITERIMA</span>
                                        @else
                                            <span class="text-xs font-bold text-red-600">DITOLAK</span>
                                            <span class="block text-[10px] text-red-500 max-w-[150px] truncate" title="{{ $pendaftar->alasan_ditolak }}">Alasan: {{ $pendaftar->alasan_ditolak }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-xs">
                                        {{ $pendaftar->created_at->format('d M Y') }}<br>
                                        <span class="text-navy/50">{{ $pendaftar->created_at->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="px-4 py-4 no-print align-top">
                                        <div class="flex flex-col gap-2">

                                            <!-- Tombol Lihat File (Memicu Modal Pop-up) -->
                                            <div class="flex flex-col gap-1 mb-1">
                                                @if($pendaftar->bukti_pembayaran)
                                                    <button type="button" onclick="openModal('Bukti Pembayaran - {{ addslashes($pendaftar->nama_lengkap) }}', 'image', '{{ asset($pendaftar->bukti_pembayaran) }}')" class="w-full text-[10px] bg-gray-200 text-navy px-2 py-1.5 font-bold uppercase rounded hover:bg-gray-300 transition-colors">Lihat Bukti Bayar</button>
                                                @endif

                                                @if($pendaftar->surat_rekomendasi)
                                                    <button type="button" onclick="openModal('Surat MGMP - {{ addslashes($pendaftar->nama_lengkap) }}', 'pdf', '{{ asset($pendaftar->surat_rekomendasi) }}')" class="w-full text-[10px] bg-blue-100 text-blue-700 px-2 py-1.5 font-bold uppercase rounded hover:bg-blue-200 transition-colors">Lihat Surat MGMP</button>
                                                @endif
                                            </div>

                                            @if($pendaftar->status === 'Menunggu')
                                                <div class="flex gap-2">
                                                    <!-- Tombol Terima -->
                                                    <form action="{{ url('/pendaftaran/'.$pendaftar->id.'/terima') }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit" class="w-full text-[10px] bg-green-500 text-white px-2 py-1.5 font-bold uppercase rounded hover:bg-green-600">Terima</button>
                                                    </form>

                                                    <!-- Tombol Tolak (Pemicu JS) -->
                                                    <button type="button" onclick="tolakPeserta({{ $pendaftar->id }})" class="flex-1 text-[10px] bg-red-500 text-white px-2 py-1.5 font-bold uppercase rounded hover:bg-red-600">Tolak</button>
                                                </div>

                                                <!-- Form Tersembunyi untuk Penolakan -->
                                                <form id="form-tolak-{{ $pendaftar->id }}" action="{{ url('/pendaftaran/'.$pendaftar->id.'/tolak') }}" method="POST" class="hidden">
                                                    @csrf
                                                    <input type="hidden" name="alasan_ditolak" id="alasan-input-{{ $pendaftar->id }}">
                                                </form>
                                            @elseif($pendaftar->status === 'Diterima')
                                                <span class="text-[10px] text-center font-bold text-green-600 py-1.5 border border-green-600 rounded">TERVALIDASI</span>
                                            @else
                                                <span class="text-[10px] text-center font-bold text-navy/50 py-1.5 border border-gray-300 rounded">MENUNGGU REVISI</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-navy/50 font-bold uppercase tracking-wider border-b border-gray-300">Belum ada peserta yang mendaftar.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- UI MODAL POP-UP (Awalnya Tersembunyi) -->
    <div id="fileModal" class="fixed inset-0 z-[100] hidden bg-navy/80 backdrop-blur-sm flex items-center justify-center p-4 no-print transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header Modal -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 id="modalTitle" class="font-black text-navy text-lg uppercase tracking-wider">Preview Dokumen</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-full hover:bg-red-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Konten Modal (Akan diisi Javascript) -->
            <div class="p-6 flex-1 overflow-auto bg-gray-100 flex items-center justify-center relative" id="modalContent">
                <!-- Data injeksi -->
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button onclick="closeModal()" class="bg-navy text-white px-6 py-2 rounded-lg text-xs font-bold uppercase hover:bg-yellow hover:text-navy transition-colors shadow">Tutup Preview</button>
            </div>
        </div>
    </div>

    <!-- Script Javascript untuk Popup Penolakan dan Modal Viewer -->
    <script>
        // Logika Menolak Peserta
        function tolakPeserta(id) {
            let alasan = prompt("Masukkan alasan penolakan pendaftaran (Misal: Bukti transfer buram/kurang):");
            if (alasan !== null && alasan.trim() !== "") {
                document.getElementById('alasan-input-' + id).value = alasan;
                document.getElementById('form-tolak-' + id).submit();
            } else if (alasan !== null && alasan.trim() === "") {
                alert("Alasan penolakan tidak boleh kosong!");
            }
        }

        // Logika Membuka Modal File
        function openModal(title, type, url) {
            document.getElementById('modalTitle').innerText = title;
            const contentDiv = document.getElementById('modalContent');

            // Bersihkan konten lama dan tambahkan loading state sederhana
            contentDiv.innerHTML = '<span class="text-navy/50 font-bold animate-pulse">Memuat dokumen...</span>';

            // Hapus class hidden agar modal muncul
            document.getElementById('fileModal').classList.remove('hidden');

            setTimeout(() => {
                if (type === 'image') {
                    contentDiv.innerHTML = `<img src="${url}" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-sm" alt="Preview Gambar">`;
                } else if (type === 'pdf') {
                    contentDiv.innerHTML = `<iframe src="${url}" class="w-full h-[70vh] rounded-lg shadow-sm" frameborder="0"></iframe>`;
                }
            }, 300); // Sedikit delay agar transisi mulus
        }

        // Logika Menutup Modal
        function closeModal() {
            document.getElementById('fileModal').classList.add('hidden');
            document.getElementById('modalContent').innerHTML = ''; // Hapus isi agar berhenti me-load (terutama iframe)
        }

        // Tutup modal jika user klik area hitam (backdrop)
        document.getElementById('fileModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
@endsection
