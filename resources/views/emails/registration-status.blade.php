<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pendaftaran</title>
</head>
<body style="margin:0; padding:0; background-color:#EDEFF3; font-family: Arial, Helvetica, sans-serif;">
    @php
        $isDiterima = in_array($data['status'], ['Diterima', 'Valid']);
        $accentColor = $isDiterima ? '#16A34A' : '#DC2626';
        $bgLight = $isDiterima ? '#F0FDF4' : '#FEF2F2';
        $borderLight = $isDiterima ? '#BBF7D0' : '#FECACA';
    @endphp
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EDEFF3; padding: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background-color:#0B1528; padding: 28px 32px;">
                            <p style="margin:0; color:#FFEB00; font-size:11px; font-weight:bold; text-transform:uppercase; letter-spacing:1.5px;">FTI Lampung — {{ $data['jenis'] }}</p>
                            <h1 style="margin:8px 0 0; color:#ffffff; font-size:20px; font-weight:900; text-transform:uppercase;">
                                {{ $isDiterima ? 'Pendaftaran Anda Diterima' : 'Pendaftaran Anda Ditolak' }}
                            </h1>
                        </td>
                    </tr>

                    <!-- STATUS BADGE -->
                    <tr>
                        <td style="padding: 32px 32px 0;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color:{{ $bgLight }}; border: 1px solid {{ $borderLight }}; border-radius: 10px; padding: 10px 18px;">
                                        <span style="color:{{ $accentColor }}; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">
                                            Status: {{ strtoupper($data['status']) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding: 20px 32px 32px;">
                            <p style="margin:0 0 16px; color:#333; font-size:14px; line-height:1.6;">
                                Halo <strong>{{ $data['nama_pendaftar'] }}</strong>,
                            </p>

                            @if($isDiterima)
                                <p style="margin:0 0 20px; color:#333; font-size:14px; line-height:1.6;">
                                    Selamat! Berkas pendaftaran Anda untuk kegiatan <strong>{{ $data['nama_kegiatan'] }}</strong> telah diperiksa dan dinyatakan <strong style="color:{{ $accentColor }};">valid / disetujui</strong> oleh panitia FTI Lampung. Anda resmi terdaftar sebagai peserta pada kegiatan ini.
                                </p>
                            @else
                                <p style="margin:0 0 12px; color:#333; font-size:14px; line-height:1.6;">
                                    Mohon maaf, setelah diperiksa oleh panitia, berkas pendaftaran Anda untuk kegiatan <strong>{{ $data['nama_kegiatan'] }}</strong> <strong style="color:{{ $accentColor }};">belum dapat kami setujui</strong> dengan rincian sebagai berikut:
                                </p>

                                @if(!empty($data['alasan_penolakan']))
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $bgLight }}; border: 1px solid {{ $borderLight }}; border-radius: 10px; margin-bottom: 20px;">
                                        <tr>
                                            <td style="padding: 16px 18px;">
                                                <p style="margin:0 0 4px; color:{{ $accentColor }}; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">Alasan Penolakan</p>
                                                <p style="margin:0; color:#333; font-size:13px; line-height:1.5;">{{ $data['alasan_penolakan'] }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                <p style="margin:0 0 20px; color:#333; font-size:14px; line-height:1.6;">
                                    Anda dapat memperbaiki data atau mengunggah ulang bukti pembayaran yang valid melalui halaman pendaftaran, lalu kirim ulang berkas Anda untuk diperiksa kembali oleh panitia.
                                </p>
                            @endif

                            @if(!empty($data['detail']))
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F9FA; border-radius: 12px; margin-bottom: 24px;">
                                    <tr>
                                        <td style="padding: 18px 20px;">
                                            @foreach($data['detail'] as $label => $value)
                                                <p style="margin:0 0 3px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">{{ $label }}</p>
                                                <p style="margin:0 0 14px; color:#0B1528; font-size:13px; font-weight:bold;">{{ $value }}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            @if(!empty($data['link_terkait']))
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="border-radius: 10px; background-color:{{ $isDiterima ? '#0B1528' : '#0B1528' }};">
                                            <a href="{{ $data['link_terkait'] }}" target="_blank" style="display:inline-block; padding: 14px 28px; color:#FFEB00; font-size:13px; font-weight:bold; text-decoration:none; text-transform:uppercase; letter-spacing:1px;">
                                                {{ $data['link_label'] ?? 'Lihat Detail' }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding: 20px 32px; background-color:#F8F9FA; border-top: 1px solid #eee;">
                            <p style="margin:0; color:#999; font-size:11px; text-align:center;">
                                Ada pertanyaan? Hubungi panitia FTI Lampung melalui kontak resmi kami.<br>
                                Email otomatis — mohon tidak membalas email ini secara langsung.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
