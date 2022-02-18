<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Empresa;

class UserFactory extends Factory
{
    public function definition()
    {
        $rol = $this->faker->randomElement(['Gestor', 'Cliente']);
        $empresa_id = $rol === "Cliente" ? Empresa::pluck('id')[$this->faker->numberBetween(1,Empresa::count()-1)] : null;
        return [
            'empresa_id' => $empresa_id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'rol' => $rol,
            'password' => bcrypt("12345678"), // password
            'status' => null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
