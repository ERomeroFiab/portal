<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Empresa::factory(10)->create();
        \App\Models\User::create([
            "name"     => "Administrador Principal",
            "email"    => "admin@admin.com",
            "rol"      => "Administrador",
            "password" => bcrypt("12345678"),
        ]);
        \App\Models\User::factory(10)->create();
        \App\Models\RazonSocial::factory(10)->create();
        \App\Models\Factura::factory(10)->create();
        \App\Models\Gestion::factory(10)->create();
        \App\Models\Reporte::factory(10)->create();
    }
}
