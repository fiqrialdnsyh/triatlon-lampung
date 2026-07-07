<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'tempat', 'kuota', 'tanggal_pelaksanaan', 'batas_pendaftaran',
        'status', 'biaya', 'rekening', 'konfigurasi_form'
    ];

    protected $casts = [
        'biaya' => 'array',
        'konfigurasi_form' => 'array',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
