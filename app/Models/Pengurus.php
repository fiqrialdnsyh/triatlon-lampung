<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $fillable = [
        'nama', 'jabatan', 'kategori', 'nama_daerah', 'foto', 'status_cabang', 'keterangan', 'file_sk'
    ];
}
