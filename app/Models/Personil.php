<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personil extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori',
        'nama',
        'asal_daerah',
        'foto',
        'tingkat_lisensi',
        'sertifikat_lisensi',
        'ttl',
        'umur',
        'jenis_identitas',
        'nomor_identitas',
        'riwayat_prestasi',
        'kontak'
    ];
}
