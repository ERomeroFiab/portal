<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // $this->call([RazonSocialSeeder::class,]);
        \App\Models\User::create([
            "name"     => "Administrador Principal",
            "email"    => "admin@admin.com",
            "rut"      => "26306969-2",
            "rol"      => "Administrador",
            "password" => bcrypt("UexrgHDfhi"),
        ]);
        \App\Models\User::create([
            "name"     => "Consultor",
            "rut"      => "26306969-3",
            "rol"      => "Consultor",
            "password" => bcrypt("Hfhi2yh5"),
        ]);
        // \App\Models\User::create([
        //     "name"       => "Pedro",
        //     "email"      => "cliente@cliente.com",
        //     "rol"        => "Cliente",
        //     "EMPRESA_ID" => 1,
        //     "password"   => bcrypt("UexrgHDfhi"),
        // ]);

        // \App\Models\Factura::factory(80)->create();
        // \App\Models\Gestion::factory(160)->create();
        // \App\Models\Reporte::factory(400)->create();
    }
}
