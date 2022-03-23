<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function razon_social()
    {
        return $this->belongsTo('App\Models\RazonSocial', 'razon_social_id'); 
    }
}
