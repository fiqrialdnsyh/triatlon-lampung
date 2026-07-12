<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignKontribusi extends Model
{
    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
