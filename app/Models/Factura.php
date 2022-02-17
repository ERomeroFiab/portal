<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    public function gestiones()
    {
        return $this->hasMany('App\Models\Gestion', 'factura_id'); 
    }
}
