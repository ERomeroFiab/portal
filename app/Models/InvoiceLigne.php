<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLigne extends Model
{
    use HasFactory;

    public function mission_motive_eco()
    {
        return $this->belongsTo('App\Models\MissionMotiveEco', 'mission_motive_eco_id'); 
    }

    public function mission_motive()
    {
        return $this->belongsTo('App\Models\MissionMotive', 'mission_motive_id'); 
    }
    
    public function razon_social()
    {
        return $this->belongsTo('App\Models\RazonSocial', 'razon_social_id'); 
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id'); 
    }
}
