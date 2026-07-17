<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara massal, kecuali kolom 'id'
    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'tanggal_pelaksanaan',
        'lokasi',
        'kategori_lomba',
        'poster',
        'tipe',
        'status',
        'kuota_maksimal',
        'batas_pendaftaran',
        'thb_file',
        'skema_biaya',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'link_wa_grup',
        'main_event_id',
    ];

    public function mainEvent()
    {
        return $this->belongsTo(MainEvent::class, 'main_event_id');
    }
}
