<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionMotive extends Model
{
    use HasFactory;

    protected $casts = [
        'DATE_LIMITE' => 'date:d-m-Y',
    ];

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission', 'mission_id'); 
    }

    public function mission_motive_ecos()
    {
        return $this->hasMany('App\Models\MissionMotiveEco', 'mission_motive_id');
    }
}
