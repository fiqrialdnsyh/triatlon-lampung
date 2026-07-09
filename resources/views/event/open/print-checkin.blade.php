<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Check-In - {{ $event->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 1cm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white text-black p-8" onload="window.print()">

    <div class="no-print mb-6">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded font-bold">Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="bg-gray-200 text-black px-4 py-2 rounded font-bold ml-2">Tutup Tab</button>
    </div>

    <div class="border-b-4 border-black pb-4 mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-wider">Laporan Kehadiran Atlet (Check-In)</h1>
            <h2 class="text-lg font-bold text-gray-700 uppercase">{{ $event->judul }}</h2>
            <p class="text-sm font-semibold mt-1">Lokasi: {{ $event->lokasi }} | Tgl: {{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d/m/Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold uppercase text-gray-500">Dicetak Pada</p>
            <p class="text-sm font-black">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <table class="w-full text-left text-sm border-collapse border border-gray-800">
        <thead>
            <tr class="bg-gray-200 text-black uppercase font-black text-xs">
                <th class="p-3 border border-gray-800 w-12 text-center">No</th>
                <th class="p-3 border border-gray-800">Nama Atlet</th>
                <th class="p-3 border border-gray-800 w-24 text-center">BIB</th>
                <th class="p-3 border border-gray-800">Kategori Lomba</th>
                <th class="p-3 border border-gray-800 w-48 text-center">Waktu Hadir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($checkins as $checkin)
                <tr>
                    <td class="p-3 border border-gray-800 text-center font-semibold">{{ $no++ }}</td>
                    <td class="p-3 border border-gray-800 font-bold uppercase">{{ $checkin->nama_lengkap }}<br><span class="text-[10px] text-gray-500">{{ $checkin->asal_daerah }}</span></td>
                    <td class="p-3 border border-gray-800 text-center font-black">{{ $checkin->bib_name }}</td>
                    <td class="p-3 border border-gray-800 text-xs font-semibold">{{ $checkin->kategori_lomba }}</td>
                    <td class="p-3 border border-gray-800 text-center font-mono text-xs font-bold">{{ \Carbon\Carbon::parse($checkin->waktu_checkin)->format('d/m/Y H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-6 text-center font-bold uppercase tracking-widest text-gray-500 border border-gray-800">Belum ada data kehadiran</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
