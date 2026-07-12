<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];

    public function kontribusi()
    {
        return $this->hasMany(CampaignKontribusi::class);
    }

    // Total dana terkumpul (hanya yang sudah diverifikasi admin)
    public function getDanaTerkumpulAttribute()
    {
        return $this->kontribusi()->where('status', 'Diterima')->sum('nominal');
    }

    public function getJumlahDonaturAttribute()
    {
        return $this->kontribusi()->where('status', 'Diterima')->where('nominal', '>', 0)->count();
    }

    public function getPersenTercapaiAttribute()
    {
        if (!$this->target_dana || $this->target_dana == 0) return 0;
        return min(100, round(($this->dana_terkumpul / $this->target_dana) * 100));
    }
}
