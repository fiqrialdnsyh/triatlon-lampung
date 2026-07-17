<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'tanggal_pelaksanaan',
        'lokasi',
        'poster',
        'is_open_active',
        'is_kejurnas_active',
    ];

    public function subEvents()
    {
        return $this->hasMany(Event::class, 'main_event_id');
    }
}
