<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $casts = [
        'periodo_gestion' => 'date:m-Y',
        'fecha_deposito'  => 'date:d-m-Y',
    ];

    public function razon_social()
    {
        return $this->belongsTo('App\Models\RazonSocial', 'razon_social_id'); 
    }

    public function factura()
    {
        return $this->belongsTo('App\Models\Factura', 'factura_id'); 
    }

    public function reportes()
    {
        return $this->hasMany('App\Models\Reporte', 'gestion_id'); 
    }
}
