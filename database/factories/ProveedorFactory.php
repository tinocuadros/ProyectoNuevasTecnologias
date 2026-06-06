<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    protected $model = Proveedor::class;

    public function definition(): array
    {
        return [
            'codigo' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{4}'),
            'nombre' => $this->faker->company(),
            'ruc' => $this->faker->unique()->regexify('[0-9]{13}'),
            'telefono' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->email(),
            'direccion' => $this->faker->address(),
            'contacto_nombre' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'contacto_telefono' => $this->faker->phoneNumber(),
            'activo' => $this->faker->boolean(80),
        ];
    }
}
