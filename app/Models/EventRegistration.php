<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'nama_lengkap',
        'nomor_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'jenis_kelamin',
        'golongan_darah',
        'alamat',
        'asal_daerah',
        'email',
        'nomor_telepon',
        'bib_name',
        'kategori_lomba',
        'golongan_biaya',
        'nominal_bayar',
        'bukti_transfer',
        'qr_token',
        'status_pembayaran',
        'pesan_penolakan',
        'waktu_checkin',
    ];

    protected function casts(): array
    {
        return [
            'nomor_ktp' => 'encrypted',
            'waktu_checkin' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
