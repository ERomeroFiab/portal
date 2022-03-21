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
            "rol"      => "Administrador",
            "password" => bcrypt("UexrgHDfhi"),
        ]);
        // \App\Models\User::create([
        //     "name"     => "Juan",
        //     "email"    => "consultor@consultor.com",
        //     "rol"      => "Consultor",
        //     "password" => bcrypt("UexrgHDfhi"),
        // ]);
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
