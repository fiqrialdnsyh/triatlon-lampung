<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - {{ $pendaftaran->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Oswald:wght@700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        oswald: ['Oswald', 'sans-serif'],
                    },
                    colors: {
                        navy: '#0B1528',
                        cream: '#F4F1E1',
                        yellow: { DEFAULT: '#FFEB00' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #0B1528; }
        .font-oswald { font-family: 'Oswald', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-container { box-shadow: none !important; margin: 0 auto !important; background-color: #F4F1E1 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-navy p-4 md:p-12 min-h-screen flex flex-col items-center justify-center">

    <div class="w-full max-w-3xl flex justify-between items-center mb-6 no-print">
        <a href="{{ url('/pelatihan/'.$pendaftaran->pelatihan_id) }}" class="inline-flex items-center text-white/70 hover:text-yellow text-xs font-bold uppercase tracking-wider transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <button onclick="window.print()" class="bg-yellow text-navy px-6 py-2.5 font-black text-xs uppercase rounded-xl hover:bg-white transition-colors shadow-md">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="print-container w-full max-w-3xl bg-cream rounded-[2rem] shadow-2xl overflow-hidden border border-gray-200 flex flex-col md:flex-row">

        <div class="flex-1 p-8 md:p-10 flex flex-col justify-between border-b-2 md:border-b-0 md:border-r-2 border-dashed border-navy/20 relative">

            <div class="hidden md:block absolute -bottom-4 -right-4 w-8 h-8 bg-navy rounded-full"></div>
            <div class="hidden md:block absolute -top-4 -right-4 w-8 h-8 bg-navy rounded-full"></div>

            <div>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="block text-[10px] font-black text-navy/40 uppercase tracking-widest">OFFICIAL ENTRY PASS</span>
                        <h2 class="text-xl font-black text-navy uppercase tracking-wide">TRIATLON LAMPUNG</h2>
                    </div>
                    <span class="bg-navy text-yellow text-[9px] font-black uppercase px-2.5 py-1 rounded">VERIFIED</span>
                </div>

                <div class="mb-6">
                    <span class="text-[10px] font-bold text-navy/60 uppercase tracking-wider block mb-1">PROGRAM KEGIATAN:</span>
                    <h1 class="font-oswald text-2xl md:text-3xl font-bold uppercase text-navy leading-tight tracking-wide">{{ $pendaftaran->pelatihan->judul }}</h1>
                </div>

                <div class="grid grid-cols-2 gap-x-4 gap-y-4 border-t border-navy/10 pt-6">
                    <div>
                        <span class="text-[9px] font-bold text-navy/60 uppercase tracking-wider block">NAMA PESERTA</span>
                        <span class="text-sm font-black text-navy uppercase block">{{ $pendaftaran->nama_lengkap }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-navy/60 uppercase tracking-wider block">KATEGORI JALUR</span>
                        <span class="text-sm font-black text-navy uppercase block">{{ $pendaftaran->golongan_biaya }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-navy/60 uppercase tracking-wider block">ASAL DAERAH</span>
                        <span class="text-sm font-black text-navy uppercase block">{{ $pendaftaran->asal_daerah }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-navy/60 uppercase tracking-wider block">UKURAN BAJU</span>
                        <span class="text-sm font-black text-navy uppercase block">SIZE {{ $pendaftaran->ukuran_baju }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-navy/10 flex items-center justify-between text-[10px] font-semibold text-navy/60">
                <span>ID DAFTAR: #TL-{{ str_pad($pendaftaran->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span>WAKTU: {{ $pendaftaran->created_at->format('d/m/Y H:i') }} WIB</span>
            </div>
        </div>

        <div class="w-full md:w-64 bg-navy p-8 md:p-10 text-white flex flex-col justify-between items-center text-center relative">

            <div>
                <span class="block text-[9px] font-black text-yellow uppercase tracking-widest mb-6">JADWAL PELAKSANAAN</span>

                <div class="bg-white/10 rounded-2xl p-4 border border-white/10 mb-4 inline-block w-full">
                    <span class="font-oswald text-4xl font-bold block text-yellow">
                        {{ \Carbon\Carbon::parse($pendaftaran->pelatihan->tanggal_pelaksanaan)->format('d') }}
                    </span>
                    <span class="text-xs font-black uppercase tracking-wider block">
                        {{ \Carbon\Carbon::parse($pendaftaran->pelatihan->tanggal_pelaksanaan)->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>

            <div class="w-full flex flex-col items-center mt-6 md:mt-0">
                <div class="bg-white p-3 rounded-xl mb-2">
                    <div class="w-24 h-24 bg-navy flex flex-wrap p-1 gap-1 items-center justify-center opacity-90">
                        <div class="grid grid-cols-4 gap-1 w-full h-full">
                            <div class="bg-white"></div><div class="bg-navy"></div><div class="bg-white"></div><div class="bg-white"></div>
                            <div class="bg-white"></div><div class="bg-white"></div><div class="bg-navy"></div><div class="bg-navy"></div>
                            <div class="bg-navy"></div><div class="bg-white"></div><div class="bg-white"></div><div class="bg-navy"></div>
                            <div class="bg-white"></div><div class="bg-navy"></div><div class="bg-navy"></div><div class="bg-white"></div>
                        </div>
                    </div>
                </div>
                <span class="text-[9px] font-bold tracking-widest text-white/50 uppercase">SCAN FOR CHECK-IN</span>
            </div>

        </div>
    </div>

    <p class="mt-4 text-center text-xs font-semibold text-white/40 max-w-md no-print">Tip: Saat jendela cetak terbuka, centang rincian opsi "Background graphics" agar warna latar belakang tiket tetap muncul sempurna.</p>

</body>
</html>
