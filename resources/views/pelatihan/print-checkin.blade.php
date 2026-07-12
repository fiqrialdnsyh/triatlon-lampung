<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - {{ $pelatihan->judul }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .info-box p {
            margin: 5px 0;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
        .time-badge {
            font-family: monospace;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #000; color: #fff; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <h1>REKAPITULASI KEHADIRAN (CHECK-IN) PESERTA</h1>
        <p>FEDERASI TRIATHLON INDONESIA (FTI) PROVINSI LAMPUNG</p>
    </div>

    <div class="info-box">
        <p>PROGRAM PELATIHAN: {{ mb_strtoupper($pelatihan->judul) }}</p>
        <p>TANGGAL & LOKASI: {{ \Carbon\Carbon::parse($pelatihan->tanggal_pelaksanaan)->translatedFormat('d F Y') }} — {{ $pelatihan->lokasi }}</p>
        <p>TOTAL KEHADIRAN: {{ $checkins->count() }} Peserta</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Peserta</th>
                <th style="width: 15%;">Gender & Usia</th>
                <th style="width: 20%;">Asal Daerah</th>
                <th style="width: 15%;">Kategori</th>
                <th class="text-center" style="width: 20%;">Waktu Scan QR</th>
            </tr>
        </thead>
        <tbody>
            @forelse($checkins as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ mb_strtoupper($c->nama_lengkap) }}</strong><br><span style="font-size: 10px; color: #666;">Profesi: {{ $c->pekerjaan }}</span></td>
                    <td>{{ $c->jenis_kelamin }} ({{ $c->usia }} Thn)</td>
                    <td>{{ $c->asal_daerah }}</td>
                    <td>{{ $c->golongan_biaya }}</td>
                    <td class="text-center time-badge">{{ \Carbon\Carbon::parse($c->waktu_checkin)->format('d/m/Y - H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Belum ada peserta yang memindai tiket kehadiran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Panitia Pelaksana,</p>
            <div class="signature-line">
                <strong>( ........................................... )</strong>
            </div>
        </div>
    </div>

</body>
</html>
