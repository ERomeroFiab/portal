<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    public function gestion()
    {
        return $this->belongsTo('App\Models\Gestion', 'gestion_id'); 
    }
}
