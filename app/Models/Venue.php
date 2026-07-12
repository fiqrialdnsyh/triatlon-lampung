<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'daerah',
        'tingkat',
        'alamat',
        'deskripsi',
        'fasilitas',
        'rute_renang',
        'rute_sepeda',
        'rute_lari',
        'area_transisi',
        'link_maps'
    ];

    // Mengubah data JSON kembali menjadi array saat dipanggil
    protected $casts = [
        'fasilitas' => 'array',
    ];

    // Relasi: 1 Venue punya Banyak Foto
    public function photos()
    {
        return $this->hasMany(VenuePhoto::class);
    }
}
