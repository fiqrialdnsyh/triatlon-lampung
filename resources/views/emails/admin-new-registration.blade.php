<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Baru</title>
</head>
<body style="margin:0; padding:0; background-color:#EDEFF3; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EDEFF3; padding: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

                    <!-- HEADER -->
                    <tr>
                        <td style="background-color:#0B1528; padding: 28px 32px;">
                            <p style="margin:0; color:#FFEB00; font-size:11px; font-weight:bold; text-transform:uppercase; letter-spacing:1.5px;">FTI Lampung — Notifikasi Sistem</p>
                            <h1 style="margin:8px 0 0; color:#ffffff; font-size:20px; font-weight:900; text-transform:uppercase;">Pendaftaran Baru Masuk</h1>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin:0 0 20px; color:#333; font-size:14px; line-height:1.6;">
                                Halo Admin, ada pendaftaran baru yang perlu Anda verifikasi pada sistem FTI Lampung. Berikut rincian lengkapnya:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F9FA; border-radius: 12px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin:0 0 4px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">Jenis Pendaftaran</p>
                                        <p style="margin:0 0 16px; color:#0B1528; font-size:14px; font-weight:bold;">{{ $data['jenis'] }}</p>

                                        <p style="margin:0 0 4px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">Nama Kegiatan</p>
                                        <p style="margin:0 0 16px; color:#0B1528; font-size:14px; font-weight:bold;">{{ $data['nama_kegiatan'] }}</p>

                                        <p style="margin:0 0 4px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">Nama Pendaftar</p>
                                        <p style="margin:0 0 16px; color:#0B1528; font-size:14px; font-weight:bold;">{{ $data['nama_pendaftar'] }}</p>

                                        @if(!empty($data['detail']))
                                            @foreach($data['detail'] as $label => $value)
                                                <p style="margin:0 0 4px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">{{ $label }}</p>
                                                <p style="margin:0 0 16px; color:#0B1528; font-size:14px; font-weight:bold;">{{ $value }}</p>
                                            @endforeach
                                        @endif

                                        <p style="margin:0 0 4px; color:#999; font-size:10px; font-weight:bold; text-transform:uppercase; letter-spacing:1px;">Waktu Pendaftaran</p>
                                        <p style="margin:0; color:#0B1528; font-size:14px; font-weight:bold;">{{ $data['waktu_daftar'] }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px; color:#333; font-size:13px; line-height:1.6;">
                                Segera lakukan verifikasi berkas dan bukti pembayaran pendaftar ini melalui panel admin agar peserta dapat menerima kepastian status secepatnya.
                            </p>

                            @if(!empty($data['link_verifikasi']))
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="border-radius: 10px; background-color:#0B1528;">
                                            <a href="{{ $data['link_verifikasi'] }}" target="_blank" style="display:inline-block; padding: 14px 28px; color:#FFEB00; font-size:13px; font-weight:bold; text-decoration:none; text-transform:uppercase; letter-spacing:1px;">
                                                Buka Panel Verifikasi
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
                                Email otomatis dari Sistem Federasi Triathlon Indonesia — Provinsi Lampung.<br>
                                Mohon tidak membalas email ini secara langsung.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
