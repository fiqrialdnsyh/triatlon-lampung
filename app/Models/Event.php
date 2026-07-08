<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara massal, kecuali kolom 'id'
    protected $guarded = ['id'];
}
