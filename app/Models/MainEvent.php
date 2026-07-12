<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainEvent extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Satu MainEvent punya banyak sub-event (Open/Kejurnas)
    public function subEvents()
    {
        return $this->hasMany(Event::class, 'main_event_id');
    }
}
