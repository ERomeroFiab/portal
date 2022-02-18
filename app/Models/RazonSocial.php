<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    use HasFactory;

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id'); 
    }
    public function gestiones_pendientes()
    {
        return $this->hasMany('App\Models\Gestion', 'razon_social_id')->where('status', "Pendiente"); 
    }
    public function gestiones_finalizadas()
    {
        return $this->hasMany('App\Models\Gestion', 'razon_social_id')->where('status', "Finalizada"); 
    }
    public function gestiones()
    {
        return $this->hasMany('App\Models\Gestion', 'razon_social_id'); 
    }
}