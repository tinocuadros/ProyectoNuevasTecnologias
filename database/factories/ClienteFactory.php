<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'nombre' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'cedula' => $this->faker->unique()->regexify('[0-9]{10}'),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->email(),
            'direccion' => $this->faker->address(),
            'activo' => $this->faker->boolean(80),
        ];
    }
}
