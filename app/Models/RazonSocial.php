<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    use HasFactory;

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa', 'empresa_id', 'id'); 
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission', 'razon_social_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'razon_social_id', 'id');
    }

    public function gestiones()
    {
        return $this->hasMany('App\Models\Gestion', 'razon_social_id', 'id');
    }
    
    
}
