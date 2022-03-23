<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionMotiveEco extends Model
{
    use HasFactory;

    public function mission_motive()
    {
        return $this->belongsTo('App\Models\MissionMotive', 'mission_motive_id'); 
    }

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission', 'mission_id'); 
    }

    public function razon_social()
    {
        return $this->belongsTo('App\Models\RazonSocial', 'razon_social_id'); 
    }
}
