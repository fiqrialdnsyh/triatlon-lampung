<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke tabel Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi balik ke akun User pendaftar
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
