<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenuePhoto extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'path_foto'];

    // Relasi balik: Foto ini milik Venue siapa
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
