<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    public function razones_sociales()
    {
        return $this->hasMany('App\Models\RazonSocial', 'id'); 
    }

    public function representante()
    {
        return $this->hasOne('App\Models\User', 'id'); 
    }

    public function get_razones_sociales_quantity_in_text()
    {
        $texto = "";
        $cantidad = $this->razones_sociales->count();
        
        if ( $cantidad == 1 ) {
            $texto = $cantidad." Razón Social";
        } elseif ( $cantidad > 1 ) {
            $texto = $cantidad." Razones Sociales";
        } else {
            $texto = "Sin Razones Sociales.";
        }
        return $texto;
    }
}
