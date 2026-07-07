<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'pelatihan_id', 'nama_lengkap', 'jenis_kelamin', 'usia',
        'pengalaman_melatih', 'pengalaman_lainnya', 'pekerjaan', 'asal_daerah',
        'ukuran_baju', 'golongan_biaya', 'bukti_pembayaran', 'surat_rekomendasi',
        'status', 'alasan_ditolak'
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
