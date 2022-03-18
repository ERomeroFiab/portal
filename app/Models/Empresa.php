<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    public function razones_sociales()
    {
        return $this->hasMany('App\Models\RazonSocial', 'empresa_id'); 
    }

    public function representante()
    {
        return $this->hasOne('App\Models\User', 'empresa_id'); 
    }

    public function get_razones_sociales_quantity_in_text()
    {
        if ( $this->razones_sociales->count() <= 1 ) {
            return $this->razones_sociales->count()." Razón Social";
        }
        return $this->razones_sociales->count()." Razones Sociales";
    }
}
