<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $casts = [
        'DATE_DEBUT'         => 'date:d-m-Y',
        'DATE_DEBUT_ANALYSE' => 'date:d-m-Y',
        'DATE_FIN_ANALYSE'   => 'date:d-m-Y',
        'DATE_FIN_MISSION'   => 'date:d-m-Y',
        'DEADLINE'           => 'date:d-m-Y',
    ];

    public function razon_social()
    {
        return $this->belongsTo('App\Models\RazonSocial', 'razon_social_id'); 
    }

    public function mission_motives()
    {
        return $this->hasMany('App\Models\MissionMotive', 'mission_id');
    }
}
