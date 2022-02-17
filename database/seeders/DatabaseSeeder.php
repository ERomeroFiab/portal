<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Empresa::factory(10)->create();
        \App\Models\User::factory(10)->create();
        \App\Models\RazonSocial::factory(10)->create();
        \App\Models\Factura::factory(10)->create();
        \App\Models\Gestion::factory(10)->create();
        \App\Models\Reporte::factory(10)->create();
    }
}
